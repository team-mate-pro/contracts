<?php

declare(strict_types=1);

namespace TeamMatePro\Contracts\Tests\Unit\ValueObject;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\ValueObject\Email;

#[CoversClass(Email::class)]
final class EmailTest extends TestCase
{
    #[Test]
    public function itCreatesEmailWithValidAddress(): void
    {
        $email = new Email('user@example.com');

        $this->assertSame('user@example.com', $email->getEmail());
        $this->assertSame('user@example.com', $email->toString());
    }

    #[Test]
    public function itCreatesEmailFromString(): void
    {
        $email = Email::fromString('test@domain.com');

        $this->assertSame('test@domain.com', $email->getEmail());
    }

    #[Test]
    public function itNormalizesEmailToLowercase(): void
    {
        $email = new Email('User@EXAMPLE.COM');

        $this->assertSame('user@example.com', $email->getEmail());
    }

    #[Test]
    public function itTrimsWhitespace(): void
    {
        $email = new Email('  user@example.com  ');

        $this->assertSame('user@example.com', $email->getEmail());
    }

    #[Test]
    public function itGetsLocalPart(): void
    {
        $email = new Email('john.doe@example.com');

        $this->assertSame('john.doe', $email->getLocalPart());
    }

    #[Test]
    public function itGetsDomain(): void
    {
        $email = new Email('user@example.com');

        $this->assertSame('example.com', $email->getDomain());
    }

    #[Test]
    public function itChecksIfEmailHasSpecificDomain(): void
    {
        $email = new Email('user@example.com');

        $this->assertTrue($email->hasDomain('example.com'));
        $this->assertTrue($email->hasDomain('EXAMPLE.COM'));
        $this->assertFalse($email->hasDomain('other.com'));
    }

    #[Test]
    public function itChecksEmailEquality(): void
    {
        $email1 = new Email('user@example.com');
        $email2 = new Email('USER@EXAMPLE.COM');
        $email3 = new Email('other@example.com');

        $this->assertTrue($email1->equals($email2));
        $this->assertFalse($email1->equals($email3));
    }

    #[Test]
    public function itConvertsToString(): void
    {
        $email = new Email('user@example.com');

        $this->assertSame('user@example.com', (string) $email);
    }

    #[Test]
    public function itThrowsExceptionForEmptyEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Email address cannot be empty');

        new Email('');
    }

    #[Test]
    public function itThrowsExceptionForWhitespaceOnlyEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Email address cannot be empty');

        new Email('   ');
    }

    #[Test]
    public function itThrowsExceptionForInvalidEmailFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address format');

        new Email('invalid-email');
    }

    #[Test]
    public function itThrowsExceptionForEmailWithoutAtSymbol(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address format');

        new Email('userexample.com');
    }

    #[Test]
    public function itThrowsExceptionForEmailWithoutDomain(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address format');

        new Email('user@');
    }

    #[Test]
    public function itThrowsExceptionForEmailWithoutLocalPart(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address format');

        new Email('@example.com');
    }

    #[Test]
    public function itThrowsExceptionForEmailWithSpaces(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address format');

        new Email('user name@example.com');
    }

    #[Test]
    public function itThrowsExceptionForEmailWithMultipleAtSymbols(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address format');

        new Email('user@@example.com');
    }

    #[Test]
    public function emailIsReadonly(): void
    {
        $email = new Email('user@example.com');

        // Reflection to verify readonly class
        $reflection = new \ReflectionClass($email);
        $this->assertTrue($reflection->isReadOnly());
    }

    #[Test]
    public function itImplementsStringableInterface(): void
    {
        $email = new Email('user@example.com');

        $this->assertInstanceOf(\Stringable::class, $email);
    }

    #[Test]
    #[DataProvider('validEmailsProvider')]
    public function itAcceptsValidEmails(string $emailAddress, string $expectedNormalized): void
    {
        $email = new Email($emailAddress);

        $this->assertSame($expectedNormalized, $email->getEmail());
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function validEmailsProvider(): array
    {
        return [
            'simple' => ['user@example.com', 'user@example.com'],
            'with dots' => ['john.doe@example.com', 'john.doe@example.com'],
            'with plus' => ['user+tag@example.com', 'user+tag@example.com'],
            'with numbers' => ['user123@example456.com', 'user123@example456.com'],
            'with dash' => ['first-last@example.com', 'first-last@example.com'],
            'subdomain' => ['user@mail.example.com', 'user@mail.example.com'],
            'uppercase' => ['USER@EXAMPLE.COM', 'user@example.com'],
            'mixed case' => ['User.Name@Example.Com', 'user.name@example.com'],
            'with underscore' => ['user_name@example.com', 'user_name@example.com'],
            'short domain' => ['a@b.co', 'a@b.co'],
        ];
    }

    #[Test]
    #[DataProvider('invalidEmailsProvider')]
    public function itRejectsInvalidEmails(string $emailAddress): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Email($emailAddress);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidEmailsProvider(): array
    {
        return [
            'no at symbol' => ['userexample.com'],
            'double at' => ['user@@example.com'],
            'no domain' => ['user@'],
            'no local part' => ['@example.com'],
            'spaces in local' => ['user name@example.com'],
            'spaces in domain' => ['user@exam ple.com'],
            'starts with dot' => ['.user@example.com'],
            'ends with dot' => ['user.@example.com'],
            'double dots' => ['user..name@example.com'],
        ];
    }

    #[Test]
    #[DataProvider('domainCheckProvider')]
    public function itChecksDomainCorrectly(string $emailAddress, string $checkDomain, bool $expected): void
    {
        $email = new Email($emailAddress);

        $this->assertSame($expected, $email->hasDomain($checkDomain));
    }

    /**
     * @return array<string, array{string, string, bool}>
     */
    public static function domainCheckProvider(): array
    {
        return [
            'exact match' => ['user@example.com', 'example.com', true],
            'case insensitive match' => ['user@example.com', 'EXAMPLE.COM', true],
            'no match' => ['user@example.com', 'other.com', false],
            'subdomain no match' => ['user@mail.example.com', 'example.com', false],
            'subdomain exact match' => ['user@mail.example.com', 'mail.example.com', true],
        ];
    }

    #[Test]
    #[DataProvider('localPartProvider')]
    public function itExtractsLocalPartCorrectly(string $emailAddress, string $expectedLocal): void
    {
        $email = new Email($emailAddress);

        $this->assertSame($expectedLocal, $email->getLocalPart());
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function localPartProvider(): array
    {
        return [
            'simple' => ['user@example.com', 'user'],
            'with dots' => ['john.doe@example.com', 'john.doe'],
            'with plus' => ['user+tag@example.com', 'user+tag'],
            'with numbers' => ['user123@example.com', 'user123'],
            'with dash' => ['first-last@example.com', 'first-last'],
            'with underscore' => ['user_name@example.com', 'user_name'],
        ];
    }

    #[Test]
    #[DataProvider('domainExtractionProvider')]
    public function itExtractsDomainCorrectly(string $emailAddress, string $expectedDomain): void
    {
        $email = new Email($emailAddress);

        $this->assertSame($expectedDomain, $email->getDomain());
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function domainExtractionProvider(): array
    {
        return [
            'simple' => ['user@example.com', 'example.com'],
            'subdomain' => ['user@mail.example.com', 'mail.example.com'],
            'short' => ['a@b.co', 'b.co'],
            'long subdomain' => ['user@mail.inbox.example.com', 'mail.inbox.example.com'],
        ];
    }
}
