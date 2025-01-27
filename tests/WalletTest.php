<?php

use PHPUnit\Framework\TestCase;
use App\Entity\Wallet;

class WalletTest extends TestCase
{
    public function testConstructor(): void
    {
        $wallet = new Wallet('USD');
        $this->assertEquals(0, $wallet->getBalance());
        $this->assertEquals('USD', $wallet->getCurrency());
    }

    public function testSetBalance(): void
    {
        $wallet = new Wallet('USD');
        $wallet->setBalance(100);

        $this->assertEquals(100, $wallet->getBalance());
    }

    public function testSetBalanceInvalid(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid balance');

        $wallet = new Wallet('USD');
        $wallet->setBalance(-50);
    }

    public function testSetCurrency(): void
    {
        $wallet = new Wallet('USD');
        $this->assertEquals('USD', $wallet->getCurrency());
    }

    public function testSetCurrencyInvalid(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid currency');

        new Wallet('GBP');
    }

    public function testAddFund(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(50);

        $this->assertEquals(50, $wallet->getBalance());
    }

    public function testAddFundInvalid(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid amount');

        $wallet = new Wallet('USD');
        $wallet->addFund(-20);
    }

    public function testRemoveFund(): void
    {
        $wallet = new Wallet('USD');
        $wallet->addFund(100);
        $wallet->removeFund(50);

        $this->assertEquals(50, $wallet->getBalance());
    }

    public function testRemoveFundInsufficient(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient funds');

        $wallet = new Wallet('USD');
        $wallet->removeFund(50);
    }

    public function testRemoveFundInvalid(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid amount');

        $wallet = new Wallet('USD');
        $wallet->removeFund(-10);
    }
}
