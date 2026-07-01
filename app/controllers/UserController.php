<?php

class UserController
{
    private UserService $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }
    
    
    public function getAllUserByPage(): void
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

        if ($page < 1) $page = 1;
        if ($limit < 1) $limit = 10;

        $result = $this->userService->getAllUserByPage($page, $limit);

        $status = 200;
        Response::json([
            "page" => $page,
            "limit" => $limit,
            "data" => $result
        ], $status);
        return;
    }
    public function getUserByUsername(): void
    {
        $username = isset($_GET['username']) ? $_GET['username'] : null;
        
        if (!$username) {
            $status = 400;
            Response::json(["message" => "Username is required"], $status);
            return;
        }

        $user = $this->userService->getUserByUsername($username);

        if ($user === null){
            
            $status = 404;
            Response::json(["message" => "User not found"], $status);
            return;
        }

        $status = 200;
        Response::json(["data" => $user], $status);
        return;
    }

    public function deleteUserByUsername(): void {
        $username = isset($_GET['username']) ? $_GET['username'] : null;
        if (!$username) {
            $status = 400;
            Response::json(["message" => "Username is required"], $status);
            return;
        }
        $result = $this->userService->deleteUserByUsername($username);

        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function updateUser() {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input.'
            ], 400);
            return;
        }

        $request = new ModifyUserDtoRequest($data);
        $result = $this->userService->updateUser($request);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function updateAccountStatus() {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null || !isset($data['username']) || !isset($data['status'])) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input or missing username/status.'
            ], 400);
            return;
        }

        $result = $this->userService->updateAccountStatus($data['username'], $data['status']);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function updateRole() {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null || !isset($data['username']) || !isset($data['role'])) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input or missing username/role.'
            ], 400);
            return;
        }

        $result = $this->userService->updateRole($data['username'], $data['role']);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }
}
