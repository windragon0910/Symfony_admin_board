<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Product;

class LoadProducts extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 50;
    }

    public function load(ObjectManager $manager)
    {
        foreach (range(0, 99) as $i) {
            $product = new Product();
            $product->setEnabled((rand(1, 1000) % 10) < 7);
            $product->setName($this->getRandomName());
            $product->setPrice($this->getRandomPrice());
            $product->setTags($this->getRandomTags());
            $product->setEan($this->getRandomEan());
            $product->setImage('image'.($i % 10).'.jpg');
            $product->setDescription($this->getRandomDescription());
            $product->setCategories($this->getRandomCategories());

            $this->addReference('product-'.$i, $product);
            $manager->persist($product);
        }

        $manager->flush();
    }

    private function getRandomTags()
    {
        $tags = array(
            'books',
            'electronics',
            'GPS',
            'hardware',
            'laptops',
            'monitors',
            'movies',
            'music',
            'printers',
            'smartphones',
            'software',
            'toys',
            'TV & video',
            'videogames',
            'wearables',
        );

        $numTags = rand(2, 4);
        shuffle($tags);

        return array_slice($tags, 0, $numTags - 1);
    }

    private function getRandomEan()
    {
        $start = 100000000000;
        $end = 999999999999;

        $ean13 = (string) mt_rand($start, $end);

        $checksum = 0;
        foreach (str_split(strrev($ean13)) as $pos => $val) {
            $checksum += $val * (3 - 2 * ($pos % 2));
        }
        $checksum = ((10 - ($checksum % 10)) % 10);

        return $ean13.$checksum;
    }

    private function getRandomName()
    {
        $words = array(
            'Lorem', 'Ipsum', 'Sit', 'Amet', 'Adipiscing', 'Elit',
            'Vitae', 'Velit', 'Mauris', 'Dapibus', 'Suscipit', 'Vulputate',
            'Eros', 'Diam', 'Egestas', 'Libero', 'Platea', 'Dictumst',
            'Tempus', 'Commodo', 'Mattis', 'Donec', 'Posuere', 'Eleifend',
        );

        $numWords = 2;
        shuffle($words);

        return 'Product '.implode(' ', array_slice($words, 0, $numWords));
    }

    private function getRandomPrice()
    {
        $cents = array('00', '29', '39', '49', '99');

        return (float) rand(2, 79).'.'.$cents[array_rand($cents)];
    }

    private function getRandomDescription()
    {
        $phrases = array(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'Pellentesque vitae velit ex.',
            'Mauris dapibus, risus quis suscipit vulputate, eros diam egestas libero, eu vulputate eros eros eu risus.',
            'In hac habitasse platea dictumst.',
            'Morbi tempus commodo mattis.',
            'Donec vel elit dui.',
            'Ut suscipit posuere justo at vulputate.',
            'Phasellus id porta orci.',
            'Ut eleifend mauris et risus ultrices egestas.',
            'Aliquam sodales, odio id eleifend tristique, urna nisl sollicitudin urna, id varius orci quam id turpis.',
            'Nulla porta lobortis ligula vel egestas.',
            'Curabitur aliquam euismod dolor non ornare.',
            'Nunc et feugiat lectus.',
            'Nam porta porta augue.',
            'Sed varius a risus eget aliquam.',
            'Nunc viverra elit ac laoreet suscipit.',
            'Pellentesque et sapien pulvinar, consectetur eros ac, vehicula odio.',
        );

        $numPhrases = rand(5, 10);
        shuffle($phrases);

        return implode(' ', array_slice($phrases, 0, $numPhrases - 1));
    }

    private function getRandomCategories()
    {
        $categories = array();
        $numCategories = rand(1, 4);
        $allCategoryIds = range(1, 100);
        $selectedCategoryIds = array_rand($allCategoryIds, $numCategories);

        foreach ((array) $selectedCategoryIds as $categoryId) {
            $categories[] = $this->getReference('subcategory-'.$categoryId);
        }

        return $categories;
    }
}
