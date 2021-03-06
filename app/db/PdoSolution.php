<?php

namespace App\Db;

/**
 * Реализовать все запросы с помощью pdo
 * @see http://php.net/manual/ru/class.pdo.php
 *
 * Class PdoSolution
 * @package App\Db
 */
class PdoSolution
{
    /**
     * @var PdoConnection
     */
    protected $connection;

    public function __construct()
    {
        $this->connection = new PdoConnection("sqlite:" . env("DB_DATABASE"));
    }

    /**
     * Найти названия тегов по id вакансии
     *
     * @param  int $vacancyId
     * @return array
     */
    public function findVacancyTags($vacancyId)
    {
        $sql = "SELECT tags.name " .
            "FROM vacancies, vacancies_tags, tags " .
            "WHERE vacancies.id = vacancies_tags.vacancyId AND tags.id = vacancies_tags.tagId AND vacancyId = $vacancyId";
        return $this->connection->query($sql)->fetchAll(\PDO::FETCH_COLUMN, 0);
    }

    /**
     * Найти активные вакансии с тегами. Результат сгруппировать по id вакансии в виде:
     * vacancyId => [tagName, tagName]
     *
     * @return array
     */
    public function findVacanciesWithTags()
    {
        $sql = "SELECT vacancyId, tags.name " .
            "FROM vacancies, vacancies_tags, tags " .
            "WHERE vacancies.id = vacancies_tags.vacancyId AND tags.id = vacancies_tags.tagId AND isActive = 'Y'";
        $result = [];

        foreach ($this->connection->query($sql)->fetchAll() as $item) {
            $result[$item['vacancyId']][] = $item['name'];
        }
        codecept_debug($result);
        return $result;
    }

    /**
     * Найти работодателей, у которых количество вакансий больше, чем $vacanciesCount.
     *
     * @param  int $vacanciesCount
     * @return array
     */
    public function findEmployersWithVacancies($vacanciesCount)
    {
        $sql = "SELECT employerId, count(id) " .
            "FROM vacancies " .
            "GROUP BY employerId " .
            "HAVING COUNT(id) > $vacanciesCount";
        return $this->connection->query($sql)->fetchAll(\PDO::FETCH_COLUMN, 0);
    }

    /**
     * Найти вакансию с максимальным количеством откликов
     *
     * @return array
     */
    public function findVacanciesWithMaxResponses()
    {
        $sql = "SELECT vacancies.id, count(*) as cnt " .
            "FROM vacancies, vacancies_responses " .
            "WHERE vacancies.id = vacancies_responses.vacancyId " .
            "GROUP BY vacancies.id " .
            "ORDER BY cnt desc " .
            "LIMIT 1";
        return $this->connection->query($sql)->fetchAll(\PDO::FETCH_COLUMN, 0)[0];
    }

    /**
     * Найти пользователей, которые не откликались на вакансии
     *
     * @return array
     */
    public function findUsersWithoutResponses()
    {
        $sql = "SELECT users.id " .
            "FROM users " .
            "WHERE id NOT IN (SELECT distinct userId FROM vacancies_responses)";
        return $this->connection->query($sql)->fetchAll(\PDO::FETCH_COLUMN, 0);
    }
}
