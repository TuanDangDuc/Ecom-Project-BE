<?php

class VariantController{
    private VariantService $variantService;

    public function __construct(
        VariantService $variantService
    ) {
        $this->variantService = $variantService;
    }

    public function index($productId): void
    {
        $result = $this->variantService->getVariantsByProductId($productId);
        Response::json($result, 200);
    }

    public function store($productId): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null) {
            Response::json(['success' => false, 'message' => 'Invalid JSON input.'], 400);
            return;
        }

        // Add productId from path parameter to the data array for validation
        $data['productId'] = (int)$productId;

        $request = new CreateVariantDtoRequest($data);
        $result = $this->variantService->createVariant($productId, $request);
        $status = $result['success'] ? 200 : 400;

        Response::json($result, $status);
    }

    public function update($id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null) {
            Response::json(['success' => false, 'message' => 'Invalid JSON input.'], 400);
            return;
        }

        $request = new UpdateVariantDtoRequest($data);
        $result = $this->variantService->updateVariant($id, $request);
        $status = $result['success'] ? 200 : 400;

        Response::json($result, $status);
    }

    public function destroy($id): void
    {
        $result = $this->variantService->deleteVariant($id);
        $status = $result['success'] ? 200 : 400;

        Response::json($result, $status);
    }
}
