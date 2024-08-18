<?php 
class Admin extends User {

    public function __construct(int $uid, string $fname, string $lname, string $email, string $phone) {
        parent::__construct($uid, $fname, $lname, $email, $phone);
    }

    // Method to manage users (add/remove/lock/unlock)
    public function manage_user(User $user, string $action): void {
        switch ($action) {
            case 'add':
                // Code to add a user
                break;
            case 'remove':
                // Code to remove a user
                break;
            case 'lock':
                // Code to lock a user
                break;
            case 'unlock':
                // Code to unlock a user
                break;
            default:
                throw new Exception("Invalid action for manage_user");
        }
        $this->log_action("$action user " . $user->get_uid());
    }
    
    // Method to manage rooms (add/remove/update)
    public function manage_room(int $roomId, string $action, array $roomDetails = []): void {
        switch ($action) {
            case 'add':
                // Code to add a room
                break;
            case 'remove':
                // Code to remove a room
                break;
            case 'update':
                // Code to update room details
                break;
            default:
                throw new Exception("Invalid action for manage_room");
        }
        $this->log_action("$action room $roomId");
    }
    
    // Method to manage services (add/remove/update)
    public function manage_service(int $serviceId, string $action, array $serviceDetails = []): void {
        switch ($action) {
            case 'add':
                // Code to add a service
                break;
            case 'remove':
                // Code to remove a service
                break;
            case 'update':
                // Code to update service details
                break;
            default:
                throw new Exception("Invalid action for manage_service");
        }
        $this->log_action("$action service $serviceId");
    }

    // Method to log actions
    public function log_action(string $action): void {
        // Code to log admin actions, e.g., write to a database or a file
        $timestamp = date('Y-m-d H:i:s');
        $log_message = "$timestamp: Admin " . $this->get_uid() . " performed action: $action";
        // Save $log_message to a log file or database
    }

    // Method to manage audit logs
    public function manage_audit_logs(): void {
        // Code to retrieve, view, or export audit logs
    }

    // Method to set session inactivity period
    public function set_session_inactivity_period(int $minutes): void {
        // Code to set session inactivity timeout
        // Example: Update a configuration setting in the database
        $this->log_action("set session inactivity period to $minutes minutes");
    }

    // Method to perform any other admin-specific tasks
    public function perform_admin_task(string $task): void {
        // Code to handle additional admin tasks
        $this->log_action("performed admin task: $task");
    }
}
?>
