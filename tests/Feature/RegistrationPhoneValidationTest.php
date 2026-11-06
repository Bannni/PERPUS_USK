<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationPhoneValidationTest extends TestCase
{
    use RefreshDatabase;

    private function getValidRegistrationPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Budi Santoso',
            'email' => 'budi_' . uniqid() . '@example.com',
            'phone' => '081234567890',
            'password' => 'Rahasia123#',
            'password_confirmation' => 'Rahasia123#',
        ], $overrides);
    }

    /**
     * Test valid phone formats (starts with optional single +, followed only by digits).
     */
    public function test_valid_phone_formats_are_accepted()
    {
        // 1. Standard Indonesian format without +
        $payload1 = $this->getValidRegistrationPayload([
            'email' => 'user1@example.com',
            'phone' => '081234567890',
        ]);
        $response1 = $this->post('/register', $payload1);
        $response1->assertRedirect(route('catalog'));
        $this->assertDatabaseHas('users', ['email' => 'user1@example.com', 'phone' => '081234567890']);

        auth()->logout();

        // 2. International format with leading single +
        $payload2 = $this->getValidRegistrationPayload([
            'email' => 'user2@example.com',
            'phone' => '+628123456789',
        ]);
        $response2 = $this->post('/register', $payload2);
        $response2->assertRedirect(route('catalog'));
        $this->assertDatabaseHas('users', ['email' => 'user2@example.com', 'phone' => '+628123456789']);
    }

    /**
     * Test that multiple '+' signs or '+' in invalid positions are rejected.
     */
    public function test_multiple_plus_signs_are_strictly_rejected()
    {
        $invalidPhones = [
            '++628123456789',   // Double plus at start
            '+++0812345678',    // Triple plus
            '0812+34567890',    // Plus in the middle
            '081234567890+',    // Plus at the end
            '+62812+345678',    // Plus at start and middle
            '+++++++++++++',    // Only plus signs
        ];

        foreach ($invalidPhones as $phone) {
            $payload = $this->getValidRegistrationPayload([
                'email' => 'test_' . uniqid() . '@example.com',
                'phone' => $phone,
            ]);

            $response = $this->post('/register', $payload);
            $response->assertSessionHasErrors('phone');
        }
    }

    /**
     * Test length limits (min 10, max 15).
     */
    public function test_phone_length_limits()
    {
        // Too short (< 10)
        $payloadShort = $this->getValidRegistrationPayload(['phone' => '0812345']);
        $this->post('/register', $payloadShort)->assertSessionHasErrors('phone');

        // Too long (> 15)
        $payloadLong = $this->getValidRegistrationPayload(['phone' => '+6281234567890123']);
        $this->post('/register', $payloadLong)->assertSessionHasErrors('phone');
    }
}
