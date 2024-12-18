<?php

require_once 'BaseModel.php';

class AuditLog extends BaseModel {
    public function record($userId, $action, $details) {
        $stmt = $this->pdo->prepare("INSERT INTO audit_logs (user_id, action, details, timestamp) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $action, $details, date('Y-m-d H:i:s')]);
    }
}
