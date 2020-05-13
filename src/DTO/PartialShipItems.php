<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusMolliePlugin\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class PartialShipItems
{
    /** @var Collection */
    private $partialShipItems;

    public function __construct()
    {
        $this->partialShipItems = new ArrayCollection();
    }

    public function setPartialShipItem(PartialShipItem $partialShipItem): void
    {
        $partialItem = $this->findPartialShipItemWhereItem($partialShipItem);

        if (null !== $partialItem) {
            $partialItem->setQuantity($partialItem->getQuantity() + 1);

            return;
        }

        $this->partialShipItems->add($partialShipItem);
    }

    public function getPartialShipItems(): Collection
    {
        return $this->partialShipItems;
    }

    public function getArrayFromObject(): array
    {
        $data = [];
        /** @var PartialShipItem $item */
        foreach ($this->getPartialShipItems() as $item) {
            $data[] = [
                'id' => $item->getLineId(),
                'quantity' => $item->getQuantity(),
            ];
        }

        return $data;
    }

    public function findPartialShipItemWhereItem(PartialShipItem $partialShipItem): ?PartialShipItem
    {
        /** @var PartialShipItem $item */
        foreach ($this->getPartialShipItems() as $item) {
            if ($item->getId() === $partialShipItem->getId()) {
                return $item;
            }
        }

        return null;
    }

    public function findById(int $id): ?PartialShipItem
    {
        /** @var PartialShipItem $item */
        foreach ($this->getPartialShipItems() as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }

        return null;
    }
}
