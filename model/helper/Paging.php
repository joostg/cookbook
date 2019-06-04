<?php
namespace cookbook\model\helper;

class Paging
{
    /**
     * @var int the currently active page number
     */
    protected $_currentPage = 1;

    /**
     * @var int how many items to show per page
     */
    protected $_limit = 25;

    /**
     * @var int the total amount of results in the data set
     */
    protected $_numResults = 0;

    /**
     * @var int the amount of paging pages to show between previous and next
     */
    protected $_pagingPages = 5;

    /**
     * @var queryString the querystring to work with, used to generate the paging links
     */
    protected $_qs;

    public function __construct(queryString $qs)
    {
        $this->_qs = $qs;
    }

    /**
     * @param int $limit
     *
     * @return bool true if limit was set correctly, false otherwise
     */
    public function setLimit($limit)
    {
        if (!is_numeric($limit)) {
            return false;
        }

        $limit = (int)$limit;

        if ($limit < 1) {
            $limit = 1;
        }
        if ($limit > 500) {
            $limit = 500;
        }

        $this->_limit = $limit;

        return true;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * @param $currentPage
     *
     * @return bool true if page was set correctly, false otherwise
     */
    public function setCurrentPage($currentPage)
    {
        if (!is_numeric($currentPage)) {
            return false;
        }

        $currentPage = (int)$currentPage;

        if ($currentPage < 1) {
            $currentPage = 1;
        }
        if ($currentPage > 5000) {
            $currentPage = 5000;
        }

        $this->_currentPage = $currentPage;

        return true;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->_currentPage;
    }

    /**
     * @param int $numResults set the total number of results for this paging object
     *
     * @return bool
     */
    public function setNumResults($numResults)
    {
        if (!is_numeric($numResults)) {
            return false;
        }

        $numResults = (int)$numResults;
        if ($numResults < 0) {
            $numResults = 0;
        }
        $this->_numResults = $numResults;

        return true;
    }

    /**
     * @return int
     */
    public function getNumResults()
    {
        return $this->_numResults;
    }

    /**
     * Set the amount of paging pages to show between previous and next
     *
     * @param int $pagingPages the amount of pages to show
     *
     * @return bool
     */
    public function setPagingPages($pagingPages)
    {
        if (!is_numeric($pagingPages)) {
            return false;
        }

        $pagingPages = (int)$pagingPages;
        if ($pagingPages < 1) {
            $pagingPages = 1;
        }
        $this->_pagingPages = $pagingPages;

        return true;
    }

    /**
     * @return int
     */
    public function getPagingPages()
    {
        return $this->_pagingPages;
    }

    public function getPagingData()
    {
        $currentPage = $this->getCurrentPage();
        $previousPage = 1;
        if ($currentPage > 1) {
            $previousPage = $currentPage - 1;
        }
        $lastPage = (int)ceil($this->getNumResults() / $this->getLimit());
        $nextPage = $currentPage;
        if ($nextPage >= 1 && ($nextPage + 1) <= $lastPage) {
            $nextPage = $currentPage + 1;
        }

        $pages = array();
        $before = (int)floor($this->getPagingPages() / 2);
        if ($before) {
            // - 1 to remove currentPage
            $after = $this->getPagingPages() - 1 - $before;
            while (($currentPage - $before) <= 0) {
                $after += 1;
                $before -= 1;
            }

            while (($currentPage + $after) > $lastPage) {
                $after -= 1;
            }
        }

        for ($i = ($currentPage - $before); $i <= ($currentPage + $after); $i++) {
            $pages[] = $i;
        }

        $paging = $this->_createPagingResult(array(
            'first'    => 1,
            'previous' => $previousPage,
            'current'  => $currentPage,
            'pages'    => $pages,
            'next'     => $nextPage,
            'last'     => $lastPage,
        ));

        return $paging;
    }

    protected function _createPagingResult(array $data)
    {
        $result = array();

        foreach ($data as $label => $page) {
            $result[$label] = $page;
            $result['qs' . ucfirst($label)] = $this->createPagingQs($page);
        }

        $result['total'] = $this->getNumResults();
        $result['limit'] = $this->getLimit();

        return $result;
    }

    protected function createPagingQs($page)
    {
        $qs = clone $this->_qs;
        $qs->set('p', $page);
        if ($this->getLimit() != 25) {
            $qs->set('l', $this->getLimit());
        }

        if (is_array($page)) {
            $pages = array();
            foreach ($page as $item) {
                $pages[] = $this->createPagingQs($item);
            }

            return $pages;
        }

        return $qs->output();
    }
}
