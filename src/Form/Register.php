<?php

namespace App\Form;

use App\Repository\UserRepository;
use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberFormat;
use Brick\PhoneNumber\PhoneNumberParseException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Register
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="16")
     */
    public $username;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min="8")
     */
    public $password;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $firstname;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $lastname;

    /**
     * @var ?string
     */
    public $phoneNumber;

    /**
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        $user = $this->userRepository->findOneBy([
            'username' => $this->username,
        ]);
        if ($user !== null) {
            $context
                ->buildViolation('The username is already registered.')
                ->atPath('username')
                ->addViolation();
        }

        $user = $this->userRepository->findOneBy([
            'email' => $this->email,
        ]);
        if ($user !== null) {
            $context
                ->buildViolation('The email is already registered.')
                ->atPath('email')
                ->addViolation();
        }

        if (!empty($this->phoneNumber)) {
            try {
                $number = PhoneNumber::parse($this->phoneNumber);
                if (!$number->isValidNumber()) {
                    $context
                        ->buildViolation('The phoneNumber is not a valid.')
                        ->atPath('phoneNumber')
                        ->addViolation();
                }
                $this->phoneNumber = $number->format(PhoneNumberFormat::E164);
                $user = $this->userRepository->findOneBy([
                    'phoneNumber' => $this->phoneNumber,
                ]);
                if ($user !== null) {
                    $context
                        ->buildViolation('The phoneNumber is already registered.')
                        ->atPath('phoneNumber')
                        ->addViolation();
                }
            } catch (PhoneNumberParseException $exception) {
                $context
                    ->buildViolation($exception->getMessage())
                    ->atPath('phoneNumber')
                    ->addViolation();
            }
        }
    }
}
