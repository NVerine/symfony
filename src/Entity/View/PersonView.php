<?php

namespace App\Entity\View;

use App\Entity\Traits\PersonAddressTrait;
use App\Entity\Traits\PersonContactTrait;
use App\Entity\Traits\PersonTrait;
use App\Repository\View\PersonRepositoryView;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonRepositoryView::class, readOnly=true)
 * @ORM\Table(name="VW_Person")
 * @ORM\AttributeOverrides({
 *     @ORM\AttributeOverride(name="name", column=@ORM\Column(name="person_name")),
 * })
 */
class PersonView
{
    use PersonTrait, PersonAddressTrait, PersonContactTrait {
        PersonTrait::getId insteadof PersonAddressTrait, PersonContactTrait;
    }
}