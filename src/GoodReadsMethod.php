<?php
namespace aalfiann\EbookAPI;

use aalfiann\EbookAPI\Helper;

/**
 * GoodReads API Method (unstable)
 * Note: 
 * - This class is unstable because code will change in the future depends on GoodReads.
 * - Last updated and tested at 20 April 2019.
 */
class GoodReadsMethod extends GoodReads {
    
    /**
     * Get details for a given author.
     *
     * @param  integer $authorId
     * @return array
     */
    public function getAuthor($authorId)
    {
        return $this->path('author/show')->id($authorId)->send()->getResponse();
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
        return $this->path('author/list')->id($authorId)->page($page)->send()->getResponse();
    }
    
    /**
     * Get details for a given book.
     *
     * @param  integer $bookId
     * @return array
     */
    public function getBook($bookId)
    {
        return $this->path('book/show')->id($bookId)->send()->getResponse();
    }

    /**
     * Search book.
     *
     * @param  string $query    Query data to search by title name|author name|genre name|isbn code
     * @param  integer $page    Optional page offset, 1-N
     * @return array
     */
    public function searchBook($query,$page=1)
    {
        return $this->path('search/index.xml')->q($query)->searchField('all')
            ->page($page)->send()->getResponse();
    }

    /**
     * Search books by title.
     *
     * @param  string $title    Title name
     * @param  integer $page    Optional page offset, 1-N
     * @return array
     */
    public function searchBookByTitle($title,$page=1)
    {
        return $this->path('search/index.xml')->q($title)->searchField('title')
            ->page($page)->send()->getResponse();
    }
    
    /**
     * Search books by author.
     *
     * @param  string $author   Author name
     * @param  integer $page    Optional page offset, 1-N
     * @return array
     */
    public function searchBookByAuthor($author,$page=1)
    {
        return $this->path('search/index.xml')->q($author)->searchField('author')
            ->page($page)->send()->getResponse();
    }

    /**
     * Search books by genre.
     *
     * @param  string $genre    Genre name
     * @param  integer $page    Optional page offset, 1-N
     * @return array
     */
    public function searchBookByGenre($genre,$page=1)
    {
        return $this->path('search/index.xml')->q($genre)->searchField('genre')
            ->page($page)->send()->getResponse();
    }
    
    /**
     * Get details for a given book by ISBN.
     *
     * @param  string $isbn
     * @return array
     */
    public function getBookByISBN($isbn)
    {
        return $this->path('book/isbn/'.urlencode($isbn))->send()->getResponse();
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
        return $this->path('book/title')->author($author)->title(urlencode($title))->send()->getResponse();
    }
    
    /**
     * Get details for a given user.
     *
     * @param  integer $userId
     * @return array
     */
    public function getUser($userId)
    {
        return $this->path('user/show')->id($userId)->send()->getResponse();
    }
    
    /**
     * Get details for a given user by username.
     *
     * @param  string $username
     * @return array
     */
    public function getUserByUsername($username)
    {
        return $this->path('user/show')->username($username)->send()->getResponse();
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
        return $this->path('review/show')->id($reviewId)->page($page)->send()->getResponse();
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
        return $this->path('review/list')->v(2)->format('xml')->id($userId)->shelf($shelf)->sort($sort)
            ->page($page)->per_page($limit)->send()->getResponse();
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
        return $this->path('review/list')->v(2)->format('xml')->id($userId)->sort($sort)
            ->page($page)->per_page($limit)->send()->getResponse();
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
