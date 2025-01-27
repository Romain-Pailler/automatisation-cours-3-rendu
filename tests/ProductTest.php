<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProductCreation(): void
    {
        $product = new Product('Banane', ['USD' => 1.2, 'EUR' => 1.1], 'food');
        
        $this->assertEquals('Banane', $product->getName());
        $this->assertEquals(['USD' => 1.2, 'EUR' => 1.1], $product->getPrices());
        $this->assertEquals('food', $product->getType());
    }

    public function testInvalidTypeThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid type');

        new Product('Souris', ['USD' => 1000], 'invalid_type');
    }

    public function testSetPricesSkipsInvalidCurrencies(): void
    {
        $product = new Product('Clavier', ['USD' => 1000, 'INVALID' => 500], 'tech');

        $this->assertEquals(['USD' => 1000], $product->getPrices());
    }

    public function testSetPricesSkipsNegativeValues(): void
    {
        $product = new Product('Ecran', ['USD' => -1000, 'EUR' => 900], 'tech');

        $this->assertEquals(['EUR' => 900], $product->getPrices());
    }

    public function testGetTVA(): void
    {
        $foodProduct = new Product('Pomme', ['USD' => 1.2], 'food');
        $techProduct = new Product('Ordinateur', ['USD' => 1000], 'tech');

        $this->assertEquals(0.1, $foodProduct->getTVA());
        $this->assertEquals(0.2, $techProduct->getTVA());
    }

    public function testListCurrencies(): void
    {
        $product = new Product('Bonbon', ['USD' => 1.2, 'EUR' => 1.1], 'food');

        $this->assertEquals(['USD', 'EUR'], $product->listCurrencies());
    }

    public function testGetPrice(): void
    {
        $product = new Product('Bonbon', ['USD' => 1.2, 'EUR' => 1.1], 'food');

        $this->assertEquals(1.2, $product->getPrice('USD'));
        $this->assertEquals(1.1, $product->getPrice('EUR'));
    }

    public function testGetPriceWithInvalidCurrencyThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid currency');

        $product = new Product('Bonbon', ['USD' => 1.2], 'food');
        $product->getPrice('INVALID');
    }

    public function testGetPriceWithUnavailableCurrencyThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Currency not available for this product');

        $product = new Product('Bonbons', ['USD' => 1.2], 'food');
        $product->getPrice('EUR');
    }
}
