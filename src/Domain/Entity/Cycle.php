<?php
namespace App\Domain\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Class Cycle
 *
 * @ORM\Table(name="cycle")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\CycleRepository")
 *
 */
class Cycle extends Activity {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 */
	protected $id;

    /**
     * @return string
     * @Groups({"activity"})
     */
    public function getType() {

        return $this::TYPE_CYCLE;
    }
}