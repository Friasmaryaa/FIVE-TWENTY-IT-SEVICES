<?php include('db_connect.php') ?>

<!-- Role-based Dashboard -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background-color: #e8f5e9; border-color: #4CAF50;">
            <div class="card-body">
                <h4 class="welcome-header">Welcome <?= $user_name ?>! <span class="badge bg-success"><?= $user_role ?></span></h4>
                <p class="text-muted">
                    <?php
                    if($_SESSION['login_type'] == 1): // Admin
                        echo "You have access to all system features and communication tools.";
                    elseif($_SESSION['login_type'] == 2): // Staff
                        echo "You can manage tickets and communicate with customers.";
                    else: // Customer
                        echo "You can create tickets and chat with our support team.";
                    endif;
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Info boxes for Admin -->
<?php if($_SESSION['login_type'] == 1): ?>
<div class="row">
    <?php
    $boxes = [
        ['label' => 'Total Customers', 'icon' => 'fas fa-users', 'count' => $conn->query("SELECT * FROM customers")->num_rows],
        ['label' => 'Total Staff', 'icon' => 'fas fa-user', 'count' => $conn->query("SELECT * FROM staff")->num_rows],
        ['label' => 'Total Departments', 'icon' => 'fas fa-columns', 'count' => $conn->query("SELECT * FROM departments")->num_rows],
        ['label' => 'Total Tickets', 'icon' => 'fas fa-ticket-alt', 'count' => $conn->query("SELECT * FROM tickets")->num_rows],
    ];

    foreach ($boxes as $box):
    ?>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box" style="background-color: #b2f2bb; color: black;">
            <span class="info-box-icon elevation-1" style="background-color: #28a745; color:white;">
                <i class="<?php echo $box['icon']; ?>"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text"><?php echo $box['label']; ?></span>
                <span class="info-box-number"><?php echo $box['count']; ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Admin Communication Dashboard -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="card-title">Recent Tickets</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get the column name that contains customer names in your database
                            // Using a more flexible query approach to handle different column names
                            $tickets = $conn->query("SELECT t.*, c.* FROM tickets t INNER JOIN customers c ON t.customer_id = c.id ORDER BY t.date_created DESC LIMIT 5");
                            while($row = $tickets->fetch_assoc()):
                                // Determine the customer name field (could be username, fullname, etc.)
                                $customer_name = isset($row['username']) ? $row['username'] : 
                                                (isset($row['fullname']) ? $row['fullname'] : 
                                                (isset($row['customer_name']) ? $row['customer_name'] : 'Customer #'.$row['customer_id']));
                            ?>
                            <tr>
                                <td><?php echo $row['id'] ?></td>
                                <td><?php echo $customer_name ?></td>
                                <td><?php echo $row['subject'] ?></td>
                                <td>
                                    <?php if($row['status'] == 0): ?>
                                        <span class="badge bg-secondary">Pending</span>
                                    <?php elseif($row['status'] == 1): ?>
                                        <span class="badge bg-primary">Processing</span>
                                    <?php elseif($row['status'] == 2): ?>
                                        <span class="badge bg-success">Resolved</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="index.php?page=view_ticket&id=<?php echo $row['id'] ?>" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="ticket_list.php?page=tickets" class="text-success">View All Tickets</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="card-title">Staff Activity</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Staff</th>
                                <th>Department</th>
                                <th>Active Tickets</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $staff = $conn->query("SELECT s.*, d.name as dept_name, 
                                (SELECT COUNT(*) FROM tickets WHERE staff_id = s.id AND status < 2) as active_tickets
                                FROM staff s 
                                LEFT JOIN departments d ON s.department_id = d.id 
                                ORDER BY s.id DESC LIMIT 5");
                            while($row = $staff->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $row['lastname'] ?></td>
                                <td><?php echo $row['dept_name'] ?></td>
                                <td><?php echo $row['active_tickets'] ?></td>
                                <td>
                                    <?php if(isset($row['last_login']) && $row['last_login'] && strtotime($row['last_login']) > strtotime('-15 minutes')): ?>
                                        <span class="badge bg-success">Online</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Offline</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="staff_list.php?page=staff" class="text-success">Manage Staff</a>
            </div>
        </div>
    </div>
</div>

<!-- Staff Dashboard -->
<?php elseif($_SESSION['login_type'] == 2): ?>
<div class="row">
    <!-- Staff Stats -->
    <div class="col-md-4">
        <div class="info-box" style="background-color: #b2f2bb; color: black;">
            <span class="info-box-icon elevation-1" style="background-color: #28a745; color:white;">
                <i class="fas fa-tasks"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">My Active Tickets</span>
                <span class="info-box-number">
                    <?php 
                    $staff_id = $_SESSION['login_id'];
                    echo $conn->query("SELECT * FROM tickets WHERE staff_id = $staff_id AND status < 2")->num_rows; 
                    ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="info-box" style="background-color: #b2f2bb; color: black;">
            <span class="info-box-icon elevation-1" style="background-color: #28a745; color:white;">
                <i class="fas fa-check-circle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Resolved Tickets</span>
                <span class="info-box-number">
                    <?php 
                    echo $conn->query("SELECT * FROM tickets WHERE staff_id = $staff_id AND status = 2")->num_rows; 
                    ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="info-box" style="background-color: #b2f2bb; color: black;">
            <span class="info-box-icon elevation-1" style="background-color: #28a745; color:white;">
                <i class="fas fa-comments"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Unread Messages</span>
                <span class="info-box-number">
                    <?php 
                    // Check if messages table exists
                    $result = $conn->query("SHOW TABLES LIKE 'messages'");
                    if($result->num_rows > 0) {
                        echo $conn->query("SELECT * FROM messages WHERE recipient = $staff_id")->num_rows;
                    } else {
                        echo "0";
                    }
                    ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Staff's Assigned Tickets -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="card-title">My Assigned Tickets</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Subject</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tickets = $conn->query("SELECT t.*, c.* 
                                FROM tickets t 
                                INNER JOIN customers c ON t.customer_id = c.id 
                                WHERE t.staff_id = $staff_id 
                                ORDER BY t.id DESC 
                                LIMIT 10");
                                
                            // If priority column doesn't exist, we simplified the order by
                            while($row = $tickets->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $row['id'] ?></td>
                                <td><?php 
                                    // Determine the customer name field (could be username, fullname, etc.)
                                    $customer_name = isset($row['username']) ? $row['username'] : 
                                                   (isset($row['fullname']) ? $row['fullname'] : 
                                                   (isset($row['customer_name']) ? $row['customer_name'] : 'Customer #'.$row['customer_id']));
                                    echo $customer_name;
                                ?></td>
                                <td><?php echo $row['subject'] ?></td>
                                <td>
                                    <?php if(isset($row['priority'])): ?>
                                        <?php if($row['priority'] == 'High'): ?>
                                            <span class="badge bg-danger">High</span>
                                        <?php elseif($row['priority'] == 'Medium'): ?>
                                            <span class="badge bg-warning">Medium</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">Low</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-info">Normal</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($row['status'] == 0): ?>
                                        <span class="badge bg-secondary">Pending</span>
                                    <?php elseif($row['status'] == 1): ?>
                                        <span class="badge bg-primary">Processing</span>
                                    <?php elseif($row['status'] == 2): ?>
                                        <span class="badge bg-success">Resolved</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo isset($row['date_created']) ? date('M d, Y', strtotime($row['date_created'])) : 'N/A' ?></td>
                                <td>
                                    <a href="index.php?page=view_ticket&id=<?php echo $row['id'] ?>" class="btn btn-sm btn-info">Respond</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="index.php?page=tickets" class="text-success">View All Tickets</a>
            </div>
        </div>
    </div>
</div>

<!-- Customer Dashboard -->
<?php else: ?>
<div class="row">
    <!-- Customer Stats -->
    <div class="col-md-4">
        <div class="info-box" style="background-color: #b2f2bb; color: black;">
            <span class="info-box-icon elevation-1" style="background-color: #28a745; color:white;">
                <i class="fas fa-ticket-alt"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">My Tickets</span>
                <span class="info-box-number">
                    <?php 
                    $customer_id = $_SESSION['login_id'];
                    echo $conn->query("SELECT * FROM tickets WHERE customer_id = $customer_id")->num_rows; 
                    ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="info-box" style="background-color: #b2f2bb; color: black;">
            <span class="info-box-icon elevation-1" style="background-color: #28a745; color:white;">
                <i class="fas fa-spinner"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Open Tickets</span>
                <span class="info-box-number">
                    <?php 
                    echo $conn->query("SELECT * FROM tickets WHERE customer_id = $customer_id AND status < 2")->num_rows; 
                    ?>
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="info-box" style="background-color: #b2f2bb; color: black;">
            <span class="info-box-icon elevation-1" style="background-color: #28a745; color:white;">
                <i class="fas fa-comments"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Unread Messages</span>
                <span class="info-box-number">
                    <?php 
                    // Check if messages table exists
                    $result = $conn->query("SHOW TABLES LIKE 'messages'");
                    if($result->num_rows > 0) {
                        echo $conn->query("SELECT * FROM messages WHERE receiver_id = $customer_id AND is_read = 0")->num_rows;
                    } else {
                        echo "0";
                    }
                    ?>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Customer's Tickets -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title">My Tickets</h3>
                <a href="index.php?page=new_ticket" class="btn btn-light btn-sm">
                    <i class="fas fa-plus"></i> New Ticket
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Subject</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Last Update</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tickets = $conn->query("SELECT t.*, d.name as dept_name 
                                FROM tickets t 
                                LEFT JOIN departments d ON t.department_id = d.id 
                                WHERE t.customer_id = $customer_id 
                                ORDER BY t.id DESC 
                                LIMIT 10");
                            while($row = $tickets->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $row['id'] ?></td>
                                <td><?php echo $row['subject'] ?></td>
                                <td><?php echo $row['dept_name'] ?></td>
                                <td>
                                    <?php if($row['status'] == 0): ?>
                                        <span class="badge bg-secondary">Pending</span>
                                    <?php elseif($row['status'] == 1): ?>
                                        <span class="badge bg-primary">Processing</span>
                                    <?php elseif($row['status'] == 2): ?>
                                        <span class="badge bg-success">Resolved</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php 
                                    if(isset($row['last_update']) && !empty($row['last_update'])) {
                                        echo date('M d, Y g:i a', strtotime($row['last_update']));
                                    } elseif(isset($row['date_created']) && !empty($row['date_created'])) {
                                        echo date('M d, Y g:i a', strtotime($row['date_created']));
                                    } else {
                                        echo 'N/A';
                                    }
                                ?></td>
                                <td>
                                    <a href="index.php?page=view_ticket&id=<?php echo $row['id'] ?>" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="index.php?page=my_tickets" class="text-success">View All My Tickets</a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Live Chat Support Box -->
<div id="liveChatContainer" style="position: fixed; bottom: 20px; right: 20px; width: 300px; border: 2px solid #4CAF50; border-radius: 10px; background: #fff; z-index: 9999;">
    <div id="chat-header" style="background: #e6f9ec; padding: 8px; border-bottom: 1px solid #4CAF50;">
        <strong>Live Chat Support</strong>
        <button onclick="closeChat()" style="float: right; border: none; background: transparent;">&times;</button>
    </div>

    <div id="chat-box" style="height: 200px; overflow-y: auto; padding: 10px;"></div>

    <div style="padding: 10px;">
        <!-- Recipient Dropdown -->
        <select id="recipient_filter" class="form-select form-select-sm" style="width: 100%; margin-bottom: 10px;">
            <?php
            if ($user_type == 3) {
                // CUSTOMER: Get default staff
                $staff = $conn->query("SELECT id, lastname FROM staff WHERE is_active = 1 ORDER BY last_login DESC LIMIT 1");
                if ($staff->num_rows > 0) {
                    $row = $staff->fetch_assoc();
                    $default_receiver = $row['id'];
                    echo "<option value='{$row['id']}' selected>{$row['lastname']}</option>";
                } else {
                    echo "<option value=''>No staff available</option>";
                }
            } else {
                // STAFF/ADMIN: Show all staff
                echo "<option value=''>Select Recipient</option>";
                $recipients = $conn->query("SELECT id, lastname FROM staff ORDER BY lastname");
                while ($row = $recipients->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['lastname']}</option>";
                }
            }
            ?>
        </select>
        <div style="display: flex;">
            <input type="text" id="message" placeholder="Type your message..." style="flex: 1; padding: 6px;" />
            <button id="sendBtn" style="background: #4CAF50; border: none; color: white; padding: 6px 10px;">&#10148;</button>
        </div>
    </div>
</div>
<button id="open-chat" onclick="openChat()">💬</button>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function closeChat() {
    document.getElementById('liveChatContainer').style.display = 'none';
}

$('#sendBtn').on('click', function () {
    const receiverId = $('#recipient_filter').val();
    const message = $('#message').val().trim();

    if (!receiverId) {
        alert('Please select a recipient first.');
        return;
    }

    if (!message) {
        alert('Message cannot be empty.');
        return;
    }

    $.ajax({
        url: 'send_message.php',
        type: 'POST',
        data: {
            receiver_id: receiverId,
            message: message
        },
        success: function(response) {
            try {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    $('#message').val('');
                    loadMessages();
                } else {
                    alert(res.message);
                }
            } catch (e) {
                alert('Unexpected response: ' + response);
            }
        },
        error: function(xhr) {
            alert('Failed to send message: ' + (xhr.responseText || 'Server error'));
        }
    });
});

function loadMessages() {
    // Optional: implement chat history loading here
    $('#chat-box').append('<div style="color: gray;">[Sent message loaded here]</div>');
}
</script>


<style>
body {
    background-color: #f5f5dc; /* beige */
    color: #000000;
}

.navbar, .topbar, .header {
    background-color: #000000; /* black top bars */
    color: rgb(189, 241, 183);
}

.welcome-header {
    color: #2e7d32;
    font-weight: 600;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.info-box {
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.info-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.table {
    margin-bottom: 0;
}

.badge {
    font-weight: 500;
    padding: 5px 8px;
}

/* Floating Messenger-Style Chat Box - Bottom Right */
#liveChatContainer {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 320px;
    height: 400px;
    background-color: #ffffff;
    border: 2px solid #4CAF50;
    border-radius: 15px;
    padding: 10px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

#chat-box {
    flex-grow: 1;
    overflow-y: auto;
    font-size: 14px;
    margin-bottom: 10px;
    border: 1px solid #e0e0e0;
    padding: 8px;
    border-radius: 8px;
    background-color: #f9f9f9;
}

#chat-status {
    font-size: 12px;
    color: #4CAF50;
    background-color: #e8f5e9;
    border-radius: 5px;
    margin-bottom: 5px;
}

#chat-box div {
    margin: 4px 0;
    padding: 6px 10px;
    border-radius: 10px;
    max-width: 80%;
}

#chat-box .outgoing {
    background-color: #e8f5e9;
    color: #212121;
    align-self: flex-end;
    margin-left: auto;
    border-bottom-right-radius: 0;
}

#chat-box .incoming {
    background-color: #f1f1f1;
    color: #212121;
    align-self: flex-start;
    margin-right: auto;
    border-bottom-left-radius: 0;
}

#chat-box .system {
    background-color: #fff8e1;
    color: #ff8f00;
    text-align: center;
    font-style: italic;
    width: 100%;
    font-size: 12px;
}

#chat-form {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

#message {
    padding: 6px 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 20px;
    outline: none;
}

#send-button {
    border-radius: 0 20px 20px 0;
    font-size: 16px;
    font-weight: bold;
}

#chat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    padding-bottom: 5px;
    border-bottom: 1px solid #e0e0e0;
    color: #4CAF50;
}

#close-chat {
    background: none;
    border: none;
    font-size: 20px;
    color: #4CAF50;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    transition: color 0.3s;
}

#close-chat:hover {
    color: #2e7d32;
}

#open-chat {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    z-index: 9998;
    display: none; /* hide by default */
}

.form-select {
    font-size: 14px;
    height: auto;
    padding: 4px 8px;
}
</style>

    <script>
    // 🟢 Update chat status
    function updateChatStatus() {
        const receiverId = $('#receiver_id').val();

        if (receiverId && receiverId > 0) {
            $.ajax({
                url: 'get_user_status.php',
                type: 'POST',
                data: { user_id: receiverId },
                dataType: 'json',
                success: function(data) {
                    if (data && data.name) {
                        const status = data.online
                            ? '<i class="fas fa-circle text-success"></i> ' + data.name + ' is online'
                            : '<i class="fas fa-circle text-secondary"></i> ' + data.name + ' is offline';
                        $('#chat-status').html(status).show();
                    } else {
                        $('#chat-status').hide();
                    }
                },
                error: function() {
                    $('#chat-status').html('<i class="fas fa-circle text-success"></i> Support is available').show();
                }
            });
        } else {
            $('#chat-status').hide();
        }
    }

    // 💬 UI toggles for open/close chat
    window.closeChat = function () {
        document.getElementById('liveChatContainer').style.display = 'none';
        document.getElementById('open-chat').style.display = 'block';
    };

    window.openChat = function () {
        document.getElementById('liveChatContainer').style.display = 'flex';
        document.getElementById('open-chat').style.display = 'none';
    };
</script>
