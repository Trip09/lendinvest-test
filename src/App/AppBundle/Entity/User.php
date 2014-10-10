<?php

namespace App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity(
 *     fields={"email"},
 *     errorPath="email",
 *     message="This email is already in use"
 * )
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email(checkMX=true)
     * @Assert\NotBlank(message="Email can not be left blank")
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_birth", type="date")
     * @Assert\Date
     * @Assert\NotBlank(message="Date of birth can not be left blank")
     */
    private $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="account_number", type="string", length=255)
     * @Assert\NotBlank(message="Account number must contain numbers")
     */
    private $accountNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=15, nullable=true)
     */
    private $reference;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     * @return User
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set accountNumber
     *
     * @param string $accountNumber
     * @return User
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Set reference
     *
     * @param string $reference
     * @return User
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Get age
     * @return int
     */
    public function getAge()
    {

        if (empty($this->dateOfBirth)) {
            return null;
        }

        $years = date('Y') - $this->dateOfBirth->format('Y');
        $years -= date('md') > $this->dateOfBirth->format('md') ? 1 : 0;
        return (int)$years;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->getAge() < 18 || $this->getAge() > 100) {
            $context->buildViolation('Not valid age {{ value }}. Age must be between 18 and 100.')
                ->atPath('dateOfBirth')
                ->setParameter('{{ value }}', $this->getAge())
                ->addViolation();
        }

    }
}
