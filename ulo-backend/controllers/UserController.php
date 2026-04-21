<?php
/**
 * User Controller
 * Handles user profile management
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class UserController {
    
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Get user profile
     * GET /api/users/profile
     */
    public function getProfile() {
        try {
            // Verify authentication
            $authUser = AuthMiddleware::verify();
            
            // Get user details
            $user = $this->userModel->findById($authUser['id']);
            
            if (!$user) {
                Response::notFound('User not found');
            }
            
            // Return user data with decrypted sensitive information
            Response::success([
                'id' => $user['id'],
                'full_name' => $user['full_name'],
                'email' => $user['email_decrypted'] ?? $user['email'],
                'phone' => $user['phone_decrypted'] ?? $user['phone'],
                'address' => $user['address_decrypted'] ?? $user['address'],
                'role' => $user['role'],
                'status' => $user['status'],
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at']
            ], 'Profile retrieved successfully');
            
        } catch (Exception $e) {
            Response::error('Error retrieving profile: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Update user profile
     * PUT /api/users/profile
     */
    public function updateProfile() {
        try {
            // Verify authentication
            $authUser = AuthMiddleware::verify();
            
            $input = Validator::getJsonBody();
            
            // Get current user
            $user = $this->userModel->findById($authUser['id']);
            if (!$user) {
                Response::notFound('User not found');
            }
            
            // Prepare update data
            $updateData = [];
            
            if (isset($input['full_name'])) {
                Validator::required($input['full_name'], 'Full Name');
                $updateData['full_name'] = Validator::sanitize($input['full_name']);
            }
            
            if (isset($input['phone'])) {
                if (!empty($input['phone'])) {
                    Validator::phone($input['phone'], 'Phone');
                }
                $updateData['phone'] = $input['phone'];
            }
            
            if (isset($input['address'])) {
                $updateData['address'] = Validator::sanitize($input['address']);
            }
            
            if (isset($input['current_password']) && isset($input['new_password'])) {
                // Verify current password
                if (!$this->userModel->verifyPassword($input['current_password'], $user['password'])) {
                    Response::error('Current password is incorrect', 400);
                }
                
                // Validate new password
                Validator::password($input['new_password'], 'New Password');
                $updateData['password'] = $input['new_password'];
            }
            
            // Check validation errors
            if (!Validator::passed()) {
                Response::validationError(Validator::getErrors());
            }
            
            // Update user
            if ($this->userModel->update($authUser['id'], $updateData)) {
                // Get updated user
                $updatedUser = $this->userModel->findById($authUser['id']);
                
                Response::success([
                    'id' => $updatedUser['id'],
                    'full_name' => $updatedUser['full_name'],
                    'email' => $updatedUser['email_decrypted'] ?? $updatedUser['email'],
                    'phone' => $updatedUser['phone_decrypted'] ?? $updatedUser['phone'],
                    'address' => $updatedUser['address_decrypted'] ?? $updatedUser['address'],
                    'role' => $updatedUser['role'],
                    'status' => $updatedUser['status']
                ], 'Profile updated successfully');
            } else {
                Response::error('Failed to update profile', 500);
            }
            
        } catch (Exception $e) {
            Response::error('Error updating profile: ' . $e->getMessage(), 500);
        }
    }
}
