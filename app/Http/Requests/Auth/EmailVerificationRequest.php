<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Auth\Events\Verified;
use App\Models\User;

/**
 * Email Verification Request
 *
 * @package \App\Http\Requests\Auth
 */
class EmailVerificationRequest extends BaseFormRequest
{
    /**
     * @var User|null
     */
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = null;  // Initialize to store the found user
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        $userId = $this->route('id');
        $user   = User::find($userId);  // Find user by ID

        if (!$user) {
            return false;  // User not found
        }

        $this->userModel = $user;  // Save the user for further usage

        // Check if the ID and email hash match
        if (!hash_equals((string) $user->getKey(), (string) $userId)) {
            return false;
        }

        if (!hash_equals(sha1($user->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Fulfill the email verification request.
     *
     * @return void
     */
    public function fulfill()
    {
        if ($this->userModel && !$this->userModel->hasVerifiedEmail()) {
            $this->userModel->markEmailAsVerified();

            event(new Verified($this->userModel));
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        return $validator;
    }
}
