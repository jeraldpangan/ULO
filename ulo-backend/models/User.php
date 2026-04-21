<?php
/**
 * User Model
 * Handles user data operations
 */

require_once __DIR__ . '/../config/Connection.php';
require_once __DIR__ . '/../helpers/Encryptor.php';

class User {
    
    private \PDO $db;
    
    public function __construct() {
        $connection = new Connection();
        $this->db = $connection->connect();
    }
    
    /**
     * Create a new user
     */
    public function create($data) {
        try {
            // Encrypt sensitive data
            $email_encrypted = Encryptor::encryptForStorage($data['email']);
            $phone_encrypted = !empty($data['phone']) ? Encryptor::encryptForStorage($data['phone']) : null;
            $address_encrypted = !empty($data['address']) ? Encryptor::encryptForStorage($data['address']) : null;
            
            $query = "INSERT INTO users (full_name, email, email_encrypted, password, phone, phone_encrypted, address, address_encrypted, role, status) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($query);
            
            $result = $stmt->execute([
                $data['full_name'],
                $data['email'],
                $email_encrypted,
                password_hash($data['password'], PASSWORD_BCRYPT),
                $data['phone'] ?? null,
                $phone_encrypted,
                $data['address'] ?? null,
                $address_encrypted,
                $data['role'] ?? 'student',
                'active'
            ]);
            
            if ($result) {
                return $this->db->lastInsertId();
            }
            
            return false;
        } catch (Exception $e) {
            throw new Exception('Error creating user: ' . $e->getMessage());
        }
    }
    
    /**
     * Find user by email
     */
    public function findByEmail($email) {
        try {
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            return $user ?: null;
        } catch (Exception $e) {
            throw new Exception('Error finding user: ' . $e->getMessage());
        }
    }
    
    /**
     * Find user by ID
     */
    public function findById($id) {
        try {
            $query = "SELECT * FROM users WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            
            if ($user && !empty($user['email_encrypted'])) {
                $user['email_decrypted'] = Encryptor::decryptFromStorage($user['email_encrypted']);
            }
            
            if ($user && !empty($user['phone_encrypted'])) {
                $user['phone_decrypted'] = Encryptor::decryptFromStorage($user['phone_encrypted']);
            }
            
            if ($user && !empty($user['address_encrypted'])) {
                $user['address_decrypted'] = Encryptor::decryptFromStorage($user['address_encrypted']);
            }
            
            return $user ?: null;
        } catch (Exception $e) {
            throw new Exception('Error finding user: ' . $e->getMessage());
        }
    }
    
    /**
     * Update user
     */
    public function update($id, $data) {
        try {
            $updateFields = [];
            $updateParams = [];
            
            if (isset($data['full_name'])) {
                $updateFields[] = 'full_name = ?';
                $updateParams[] = $data['full_name'];
            }
            
            if (isset($data['phone'])) {
                $updateFields[] = 'phone = ?';
                $updateParams[] = $data['phone'];
                $updateFields[] = 'phone_encrypted = ?';
                $updateParams[] = Encryptor::encryptForStorage($data['phone']);
            }
            
            if (isset($data['address'])) {
                $updateFields[] = 'address = ?';
                $updateParams[] = $data['address'];
                $updateFields[] = 'address_encrypted = ?';
                $updateParams[] = Encryptor::encryptForStorage($data['address']);
            }
            
            if (isset($data['password'])) {
                $updateFields[] = 'password = ?';
                $updateParams[] = password_hash($data['password'], PASSWORD_BCRYPT);
            }
            
            if (empty($updateFields)) {
                return true;
            }
            
            $updateFields[] = 'updated_at = CURRENT_TIMESTAMP';
            $updateParams[] = $id;
            
            $query = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = ?";
            $stmt = $this->db->prepare($query);
            
            return $stmt->execute($updateParams);
        } catch (Exception $e) {
            throw new Exception('Error updating user: ' . $e->getMessage());
        }
    }
    
    /**
     * Verify password
     */
    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
    
    /**
     * Get all users (admin only)
     */
    public function getAllUsers($limit = 50, $offset = 0) {
        try {
            $query = "SELECT id, full_name, email, phone, address, role, status, created_at, updated_at FROM users LIMIT ? OFFSET ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$limit, $offset]);
            
            $users = $stmt->fetchAll();
            
            // Decrypt sensitive data for display
            foreach ($users as &$user) {
                // Don't decrypt in list view for privacy
                // Only decrypt when specifically requested
            }
            
            return $users;
        } catch (Exception $e) {
            throw new Exception('Error fetching users: ' . $e->getMessage());
        }
    }
    
    /**
     * Count total users
     */
    public function countUsers() {
        try {
            $query = "SELECT COUNT(*) as total FROM users WHERE role = 'student'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch();
            
            return $result['total'];
        } catch (Exception $e) {
            throw new Exception('Error counting users: ' . $e->getMessage());
        }
    }
}
