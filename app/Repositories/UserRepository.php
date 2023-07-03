<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements RepositoryInterface
{
    /**
     * Holds the instance of model.
     */
    public $model;

    /**
     * UserRepositoryUserRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    function create(array $data)
    {
        try {
            $result = $this->model->create($data);

            return [
                'bool' => true,
                'result' => $result
            ];
        } catch (Exception $exception) {
            return [
                'bool' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    function insert(array $data)
    {
        try {
            $result = $this->model->insert($data);

            return [
                'bool' => true,
                'result' => $result
            ];
        } catch (Exception $exception) {
            return [
                'bool' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * @param array $data
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    function update(array $data, $id)
    {
        try {
            $result = $this->model->findOrFail($id)->update($data);

            return [
                'bool' => true,
                'result' => $result
            ];
        } catch (Exception $exception) {
            return [
                'bool' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
    /**
     * @param array $data
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    function updateWithOutTryCatch(array $data, $id)
    {
        return $this->model->findOrFail($id)->update($data);
    }
    /**
     * @return mixed
     * @throws Exception
     */
    public function all()
    {
        try {
            $result = $this->model::orderBy('id', 'DESC')->get();

            return [
                'bool' => true,
                'result' => $result
            ];
        } catch (Exception $exception) {
            return [
                'bool' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function find($id): array
    {
        try {
            $result = $this->model->whereId($id)->first();

            return [
                'bool' => true,
                'result' => $result
            ];
        } catch (Exception $exception) {
            return [
                'bool' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id)
    {
        try {
            $result = $this->model->destroy($id);

            return [
                'bool' => true,
                'result' => $result
            ];
        } catch (Exception $exception) {
            return [
                'bool' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * @param $columns
     * @param $values
     * @param array $operators
     * @param array $whereIn
     * @return array
     */
    public function whereAttribute($columns, $values, $operators = [], $whereIn = []): array
    {
        try {
            $q = new $this->model;

            foreach ($columns as $key => $column) {
                if (array_key_exists($key, $operators) && !empty($operators[$key]))
                    $q = $q->where($column, $operators[$key], $values[$key]);
                else
                    $q = $q->where($column, $values[$key]);
            }

            if (count($whereIn) > 0)
                $q = $q->whereIn($whereIn['column'], $whereIn['values']);

            $result = $q->get();

            return [
                'bool' => true,
                'result' => $result
            ];
        } catch (Exception $exception) {
            return [
                'bool' => false,
                'message' => $exception->getMessage()
            ];
        }
    }


    public function getByAttribute(array $values, array $with = [], $pluck = '', $return_type = 'json', $order = '')
    {
        $queryBuilder = $this->model;
        foreach ($values as $column => $value) {
            if (strpos($column, '~') !== false) {
                $column = explode('~', $column);
                $queryBuilder = $queryBuilder->where($column[0], $column[1], $value);
            } else
                $queryBuilder = $queryBuilder->where($column, $value);
        }

        if (count($with) > 0)
            $queryBuilder = $queryBuilder->with($with);


        if ($order != '')
            $queryBuilder = $queryBuilder->orderBy($order);

        if ($pluck != '') {
            $queryBuilder = $queryBuilder->get()->pluck($pluck);

            if ($return_type == 'array')
                $queryBuilder->toArray();

            return $queryBuilder;
        }

        if ($return_type == 'array')
            return $queryBuilder->toArray();


        return $queryBuilder->get();
    }
}
