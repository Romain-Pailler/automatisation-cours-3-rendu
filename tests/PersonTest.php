<?php

use PHPUnit\Framework\TestCase;
use App\Entity\Person;
use App\Entity\Wallet;

class PersonTest extends TestCase
{
    public function testConstructor(): void
    {
        $person = new Person('Jean', 'USD');
        $this->assertEquals('Jean', $person->getName());
        $this->assertEquals('USD', $person->getWallet()->getCurrency());
        $this->assertEquals(0, $person->getWallet()->getBalance());
    }

    public function testSetName(): void
    {
        $person = new Person('Jean', 'USD');
        $person->setName('Marie');
        $this->assertEquals('Marie', $person->getName());
    }

    public function testGetWallet(): void
    {
        $person = new Person('Jean', 'USD');
        $this->assertInstanceOf(Wallet::class, $person->getWallet());
        $this->assertEquals('USD', $person->getWallet()->getCurrency());
    }

    public function testSetWallet(): void
    {
        $person = new Person('John Doe', 'USD');
        $newWallet = new Wallet('EUR');
        $person->setWallet($newWallet);

        $this->assertEquals('EUR', $person->getWallet()->getCurrency());
    }

    public function testHasFund(): void
    {
        $person = new Person('Jean', 'USD');
        $this->assertFalse($person->hasFund());

        $person->getWallet()->addFund(100);
        $this->assertTrue($person->hasFund());
    }

    public function testTransfertFund(): void
    {
        $person1 = new Person('Jean', 'USD');
        $person2 = new Person('Marie', 'USD');

        $person1->getWallet()->addFund(100);
        $person1->transfertFund(50, $person2);

        $this->assertEquals(50, $person1->getWallet()->getBalance());
        $this->assertEquals(50, $person2->getWallet()->getBalance());
    }

    public function testTransfertFundDifferentCurrencies(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Can\'t give money with different currencies');

        $person1 = new Person('Jean', 'USD');
        $person2 = new Person('Marie', 'EUR');

        $person1->transfertFund(50, $person2);
    }

    public function testDivideWallet(): void
    {
        $person1 = new Person('Jean', 'USD');
        $person2 = new Person('Marie', 'USD');
        $person3 = new Person('Toto', 'USD');

        $person1->getWallet()->addFund(100);
        $person1->divideWallet([$person2, $person3]);

        $this->assertEquals(0, $person1->getWallet()->getBalance());
        $this->assertEquals(50, $person2->getWallet()->getBalance());
        $this->assertEquals(50, $person3->getWallet()->getBalance());
    }

    public function testBuyProduct(): void
    {
        $productMock = $this->createMock(\App\Entity\Product::class);
        $productMock->method('listCurrencies')->willReturn(['USD']);
        $productMock->method('getPrice')->with('USD')->willReturn(50.0);
    
        $person = new Person('Jean', 'USD');
        $person->getWallet()->addFund(100);
    
        $person->buyProduct($productMock);
    
        $this->assertEquals(50, $person->getWallet()->getBalance());
    }
    

    public function testBuyProductInvalidCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Can\'t buy product with this wallet currency');

        $productMock = $this->createMock(\App\Entity\Product::class);
        $productMock->method('listCurrencies')->willReturn(['EUR']);

        $person = new Person('Jean', 'USD');
        $person->buyProduct($productMock);
    }
}
