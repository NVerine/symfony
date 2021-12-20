<?php

namespace App\Repository;

use App\Entity\Permissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

/**
 * @method Permissions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Permissions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Permissions[]    findAll()
 * @method Permissions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermissionsRepository extends ServiceEntityRepository
{
    public static $blocked_routes = [
        "api_comercial_index", "api_comercial_show", "api_comercial_edit",
        "api_discipline_index", "api_discipline_show",
        "api_produto_nfe", "api_nota_index", "api_produto_index", "api_produto_show", "api_produto_edit",
        "api_questions_index", "api_questions_list", "api_questions_show", "api_questions_edit", "api_test_index",
        "api_test_show", "api_test_edit", "api_trib_cfop_index", "api_trib_cfop_show", "api_trib_cfop_edit",
        "api_trib_cst_index", "api_trib_cst_show", "api_trib_cst_edit",
        "api_tipo_operacao_index", "api_tipo_operacao_show",
        "api_tipo_operacao_edit", "api_trib_ncm_index", "api_trib_ncm_show", "api_trib_ncm_edit"
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Permissions::class);
    }

    /**
     * @param null $value
     * @return array
     */
    public function mountRoutes($router)
    {
//        $router = $this->get('router');
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();
        $routes = array();

        /** @var $params Route */
        foreach ($allRoutes as $route => $params) {
            // if starts with _ it must be open
            if (!in_array($route, self::$blocked_routes) && $route[0] != "_") {
                $routes[$route] = $params->getPath();
            }
        }
        return $routes;
    }

    public function clean($group)
    {
        if (!$group > 0) {
            //erro
        }
        $permissions = $this->findBy(array('grupo' => $group));

        foreach ($permissions as $p) {
            $this->remove($p);
        }
    }
}
