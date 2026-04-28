<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class FaceCustomerController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'face_image_base64' => 'required|string',
        ]);

        $faceLabel = Str::slug($data['name'], '_');
        $discountPercent = $data['discount_percent'] ?? 0;
        $imagePath = $this->storeFaceImage($data['face_image_base64'], $faceLabel);

        $customer = Customer::updateOrCreate(
            ['face_label' => $faceLabel],
            [
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'discount_percent' => $discountPercent,
                'is_member' => $discountPercent > 0,
                'face_image' => $imagePath,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil disimpan.',
            'customer' => $customer,
        ]);
    }

    public function detectMember(Request $request)
    {
        $data = $request->validate([
            'face_label' => 'required|string|max:255',
            'score' => 'nullable|integer|min:0',
        ]);

        $customer = Customer::where('face_label', $data['face_label'])
            ->where('is_member', true)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => true,
                'found' => false,
                'message' => 'Customer member tidak ditemukan.',
            ]);
        }

        $event = [
            'id' => (string) Str::uuid(),
            'customer_id' => $customer->id,
            'name' => $customer->name,
            'phone' => $customer->phone,
            'discount_percent' => $customer->discount_percent,
            'face_label' => $customer->face_label,
            'score' => $data['score'] ?? 0,
            'detected_at' => now()->toDateTimeString(),
        ];

        Cache::put('latest_member_detection', $event, now()->addSeconds(30));

        return response()->json([
            'success' => true,
            'found' => true,
            'message' => 'Customer member terdeteksi.',
            'customer' => $event,
        ]);
    }

    private function storeFaceImage(string $base64Image, string $faceLabel): string
    {
        $cleanImage = preg_replace('/^data:image\/[a-zA-Z]+;base64,/', '', $base64Image);
        $binaryImage = base64_decode($cleanImage);

        if ($binaryImage === false) {
            abort(422, 'Format gambar base64 tidak valid.');
        }

        $directory = public_path('uploads/customers');
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $fileName = $faceLabel . '_' . time() . '.jpg';
        $fullPath = $directory . DIRECTORY_SEPARATOR . $fileName;
        file_put_contents($fullPath, $binaryImage);

        return 'uploads/customers/' . $fileName;
    }
}
