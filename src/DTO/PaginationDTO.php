<?php

namespace App\DTO;

class PaginationDTO
{


    private iterable $data;
    private int $total_items;
    private int $items_per_page;
    private int $page;
    private int $limit;

    /**
     * @param iterable $data
     * @param int $total_items
     * @param int $items_per_page
     * @param int $page
     * @param int $limit
     */
    public function __construct(iterable $data, int $total_items, int $items_per_page, int $page, int $limit)
    {
        $this->data = $data;
        $this->total_items = $total_items;
        $this->items_per_page = $items_per_page;
        $this->page = $page;
        $this->limit = $limit;
    }

    /**
     * @return iterable
     */
    public function getData(): iterable
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->total_items;
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->items_per_page;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

}
