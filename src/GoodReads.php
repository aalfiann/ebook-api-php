<?php
namespace aalfiann\EbookAPI;

use aalfiann\EbookAPI\Helper;

/**
 * GoodReads API (unstable)
 * Note: 
 * - This API is still in development process and unstable (code will change in the future).
 * - But we already stop to develop GoodReads API at this moment, maybe we will continue this later.
 * - Last updated and tested at 09 April 2019.
 */
class GoodReads extends Helper {

    /**
     * Root URL of the API (no trailing slash).
     */
    var $api_url = 'https://www.goodreads.com';
    
    /**
     * @var string Your API key.
     */
    protected $apiKey = '';
    
    /**
     * Initialise the API wrapper instance.
     *
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = (string)$apiKey;
    }
    
    /**
     * Get details for a given author.
     *
     * @param  integer $authorId
     * @return array
     */
    public function getAuthor($authorId)
    {
        return $this->request(
            'author/show',
            array(
                'key' => $this->apiKey,
                'id' => (int)$authorId
            )
        );
    }
    
    /**
     * Get books by a given author.
     *
     * @param  integer $authorId
     * @param  integer $page     Optional page offset, 1-N
     * @return array
     */
    public function getBooksByAuthor($authorId, $page = 1)
    {
        return $this->request(
            'author/list',
            array(
                'key' => $this->apiKey,
                'id' => (int)$authorId,
                'page' => (int)$page
            )
        );
    }
    
    /**
     * Get details for a given book.
     *
     * @param  integer $bookId
     * @return array
     */
    public function getBook($bookId)
    {
        return $this->request(
            'book/show',
            array(
                'key' => $this->apiKey,
                'id' => (int)$bookId
            )
        );
    }
    
    /**
     * Get details for a given book by ISBN.
     *
     * @param  string $isbn
     * @return array
     */
    public function getBookByISBN($isbn)
    {
        return $this->request(
            'book/isbn/' . urlencode($isbn),
            array(
                'key' => $this->apiKey
            )
        );
    }
    
    /**
     * Get details for a given book by title.
     *
     * @param  string $title
     * @param  string $author Optionally provide this for more accuracy.
     * @return array
     */
    public function getBookByTitle($title, $author = '')
    {
        return $this->request(
            'book/title',
            array(
                'key' => $this->apiKey,
                'title' => urlencode($title),
                'author' => $author
            )
        );
    }
    
    /**
     * Get details for a given user.
     *
     * @param  integer $userId
     * @return array
     */
    public function getUser($userId)
    {
        return $this->request(
            'user/show',
            array(
                'key' => $this->apiKey,
                'id' => (int)$userId
            )
        );
    }
    
    /**
     * Get details for a given user by username.
     *
     * @param  string $username
     * @return array
     */
    public function getUserByUsername($username)
    {
        return $this->request(
            'user/show',
            array(
                'key' => $this->apiKey,
                'username' => $username
            )
        );
    }
    
    /**
     * Get details for of a particular review
     *
     * @param  integer $reviewId
     * @param  integer $page     Optional page number of comments, 1-N
     * @return array
     */
    public function getReview($reviewId, $page = 1)
    {
        return $this->request(
            'review/show',
            array(
                'key' => $this->apiKey,
                'id' => (int)$reviewId,
                'page' => (int)$page
            )
        );
    }
    
    /**
     * Get a shelf for a given user.
     *
     * @param  integer $userId
     * @param  string  $shelf  read|currently-reading|to-read etc
     * @param  string  $sort   title|author|rating|year_pub|date_pub|date_read|date_added|avg_rating etc
     * @param  integer $limit  1-200
     * @param  integer $page   1-N
     * @return array
     */
    public function getShelf($userId, $shelf, $sort = 'title', $limit = 100, $page = 1)
    {
        return $this->request(
            'review/list',
            array(
                'v' => 2,
                'format' => 'xml',     // :( GoodReads still doesn't support JSON for this endpoint
                'key' => $this->apiKey,
                'id' => (int)$userId,
                'shelf' => $shelf,
                'sort' => $sort,
                'page' => $page,
                'per_page' => $limit
            )
        );
    }
    
    /**
     * Get all books for a given user.
     *
     * @param  integer $userId
     * @param  string  $sort   title|author|rating|year_pub|date_pub|date_read|date_added|avg_rating etc
     * @param  integer $limit  1-200
     * @param  integer $page   1-N
     * @return array
     */
    public function getAllBooks($userId, $sort = 'title', $limit = 100, $page = 1)
    {
        return $this->request(
            'review/list',
            array(
                'v' => 2,
                'format' => 'xml',     // :( GoodReads still doesn't support JSON for this endpoint
                'key' => $this->apiKey,
                'id' => (int)$userId,
                'sort' => $sort,
                'page' => $page,
                'per_page' => $limit
            )
        );
    }
    
    /**
     * Get the details of an author.
     *
     * @param  integer $authorId
     * @return array
     */
    public function showAuthor($authorId)
    {
        return $this->getAuthor($authorId);
    }
    
    /**
     * Get the details of a user.
     *
     * @param  integer $userId
     * @return array
     */
    public function showUser($userId)
    {
        return $this->getUser($userId);
    }
    
    /**
     * Get the latest books read for a given user.
     *
     * @param  integer $userId
     * @param  string  $sort   title|author|rating|year_pub|date_pub|date_read|date_added|avg_rating etc
     * @param  integer $limit  1-200
     * @param  integer $page   1-N
     * @return array
     */
    public function getLatestReads($userId, $sort = 'date_read', $limit = 100, $page = 1)
    {
        return $this->getShelf($userId, 'read', $sort, $limit, $page);
    }

}
