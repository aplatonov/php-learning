<?php

namespace App\Db;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Connection;

/**
 * Реализовать все запросы из PdoSolution с помощью query builder
 * @see https://laravel.com/docs/5.7/queries
 *
 * Class QueryBuilderSolution
 * @package App\Db
 */
class QueryBuilderSolution
{
    /**
     * @var Connection
     */
    protected $connection;

    public function __construct()
    {
        $capsule = new Capsule();
        $capsule->addConnection([
            "driver" => "sqlite",
            "database" => env("DB_DATABASE"),
        ]);
        $this->connection = $capsule->getConnection();
    }

    public function findVacancyTags($vacancyId)
    {
        $tags = $this->connection->table('tags')
            ->join('vacancies_tags', 'tags.id', '=', 'tagId')
            ->join('vacancies', 'vacancyId', '=', 'vacancies.id')
            ->select('tags.name')
            ->where('vacancies.id', '=', $vacancyId)
            ->get()
            ->pluck('name')
            ->toArray();

        return $tags;
    }

    public function findVacanciesWithTags()
    {
        $res = $this->connection->table('tags')
            ->join('vacancies_tags', 'tags.id', '=', 'tagId')
            ->join('vacancies', 'vacancyId', '=', 'vacancies.id')
            ->select('vacancies.id', 'tags.name')
            ->where('isActive', '=', 'Y')
            ->get()
            ->groupBy('id')
            ->toArray();

        $res1 = [];
        foreach($res as $key => $val) {
            foreach ($val as $intval) {
                $res1[$key][] = $intval->name;
            }
        }

        return $res1;
    }

    public function findEmployersWithVacancies($vacanciesCount)
    {
        $res = $this->connection->table('vacancies')
            ->select('employerId')
            ->groupBy('employerId')
            ->havingRaw('COUNT(employerId) > ?', [$vacanciesCount])
            ->get()
            ->pluck('employerId')
            ->toArray();

        return $res;
    }

    public function findVacanciesWithMaxResponses()
    {
        $res = $this->connection->table('vacancies')
            ->join('vacancies_responses', 'vacancies.id', '=', 'vacancyId')
            ->selectRaw('vacancies.id, COUNT(*) as cnt')
            ->groupBy('vacancies.id')
            ->orderBy('cnt', 'desc')
            ->first();

        return $res->id;
    }

    public function findUsersWithoutResponses()
    {
        $sub = $this->connection->table('vacancies_responses')
            ->select('userId')
            ->distinct()
            ->get()
            ->pluck('userId')
            ->toArray();
        $res = $this->connection->table('users')
            ->select('id')
            ->whereNotIn('id', $sub)
            ->get()
            ->pluck('id')
            ->toArray();

        return $res;
    }
}
