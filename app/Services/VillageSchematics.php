<?php

/**
 * Created by IntelliJ IDEA.
 * User: MidoriKage
 * Date: 05-May-18
 * Time: 12:47 PM
 */

namespace App\Services;

use App\Village;
use Illuminate\Support\Facades\DB;

class VillageSchematics
{
    private $name;
    private $id;
    private $lon;
    private $lat;
    private $bin;
    private $size;
    private $onlyOnce;
    private $availableRoutes;
    private $isVisited;
    private $type;
    private $isLookingFor;

    private function __construct()
    {
        $this->isVisited = false;
        $this->availableRoutes = array();
        $this->isLookingFor = true;
    }

    /**
     * TODO add routes
     * @param $json
     * @return VillageSchematics
     */
    public static function parse($json): VillageSchematics
    {
        $vS = new VillageSchematics();

        $vS->name = $json->name;
        $vS->bin = $json->bin;
        $vS->size = $json->capacity;
        $vS->onlyOnce = $json->OTO;

        $village = Village::where(["name" => $vS->name])->first();
        $vS->id = $village->id;
        $vS->lat = $village->latitude;
        $vS->lon = $village->longitude;
        $vS->type = TownType::getType($village->type);

        return $vS;
    }

    function setRoutes()
    {
        $con = DB::select(DB::raw('SELECT vill.name as destination FROM connections as conn
            INNER JOIN villages as vill ON vill.id = conn.connected_village
            where conn.route_village = ' . $this->id
        ));

        foreach ($con as $destinationObj) {
            array_push($this->availableRoutes, $destinationObj->destination);
        }
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function setSize($var)
    {
        $this->size = $var;
    }

    /**
     * @return mixed
     */
    public function getLon(): float
    {
        return $this->lon;
    }

    /**
     * @return mixed
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @return mixed
     */
    public function getBin(): bool
    {
        return $this->bin;
    }

    /**
     * @return mixed
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getOnlyOnce(): bool
    {
        return $this->onlyOnce;
    }

    /**
     * @return mixed
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isVisited(): bool
    {
        return $this->isVisited;
    }

    public function setVisitedTrue()
    {
        $this->isVisited = true;
    }

    public function setVisitedFalse()
    {
        $this->isVisited = false;
    }

    public function getAvailableRoutes()
    {
        return $this->availableRoutes;
    }

    /**
     * @return bool
     */
    public function isLookingFor(): bool
    {
        return $this->isLookingFor;
    }

    /**
     * @param bool $isLookingFor
     */
    public function setIsLookingFor(bool $isLookingFor): void
    {
        $this->isLookingFor = $isLookingFor;
    }

    public function removeRoute($route)
    {
        $key = array_search($route, $this->availableRoutes);
        unset($this->availableRoutes[$key]);
    }
//    public function getShortestPath(): AvailableRoute
//    {
//        $available = array();
//
//        dd('Shortest path needs implementation');
//
////        foreach ($this->availableRoutes as $route) {
////            if (!$visitedCollection->contains(function (VillageSchematics $village) use ($route) {
////                return $village->getName() === $route;
////            })) {
////                array_push($available, $route);
////            };
////        }
//
//        if ($available == null) {
//            dd('null available', $available, $visitedCollection, $this->availableRoutes);
//        }
//
//        $min = $available[0];
//
//        foreach ($available as $route) {
//            if ($min->getDistance() > $route->getDistance()) {
//                $min = $route;
//            }
//        }
//
//        return $min;
//    }
//
//    public function getUnvisitedPaths(Collection $visitedCollection): array
//    {
//        return collect($this->availableRoutes)->filter(function (AvailableRoute $route) use ($visitedCollection) {
//            return !$visitedCollection->contains(function (VillageSchematics $village) use ($visitedCollection, $route) {
//                return $village->getName() === $route->getTarget();
//            });
//        });
//    }
}