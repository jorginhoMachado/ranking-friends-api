<?php


namespace App\Services;


use App\Constants\Attribute;
use App\Models\Cidade;

class CidadeService extends Service
{

    /**
     * CidadeService constructor.
     * @param Cidade $model
     */
    public function __construct(Cidade $model)
    {
        $this->model = $model;
        $this->queryBuilder = $this->model->with(Attribute::ESTADO);
        parent::__construct();
    }

    public function getPaginate($data)
    {
        $fn = function($key, &$qb, &$filter) {
            if($filter->get($key)){
                $qb->where($key, $filter->get($key));
            }
        };
        $filter = $this->getFilter($data);

        $this->queryBuilder->whereHas(
            'estado.pais',
            function ($qb) use ($filter, $fn) {
                if($filter->get('no_estado')) {
                    $fn('no_estado', $qb, $filter);
                }
                if($filter->get('cd_pais')) {
                    $fn('cd_pais', $qb, $filter);
                }
                if($filter->get('sg_estado')) {
                    $fn('sg_estado', $qb, $filter);
                }
                if($filter->get('no_pais')) {
                    $fn('no_pais', $qb, $filter);
                }
                if($filter->get('no_continente')) {
                    $fn('no_continente', $qb, $filter);
                }
                if($filter->get('sg_pais')) {
                    $fn('sg_pais', $qb, $filter);
                }
            }
        );
        $filter->forget(['no_estado', 'cd_pais', 'sg_estado', 'no_pais', 'no_continente', 'sg_pais']);
        $data['filter'] = $this->setFilter($filter);
        return parent::getPaginate($data);
    }
}
