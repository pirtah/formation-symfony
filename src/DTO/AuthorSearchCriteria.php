<?php

declare(strict_types=1);

namespace App\DTO;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class AuthorSearchCriteria
{

    public ?string $name = null; // mettre un ? devant le type si le champ peut être null
	
    #[Assert\Positive()]
    #[Assert\GreaterThanOrEqual(10)]
    public int $limit = 15; // limite de résultats par page
	public int $page = 1; // nombre de page
	public string $orderBy = 'name'; // Contient le champ de tri : id / name / createdAt / updatedAt, par défaut on a mis name
    public string $direction = 'DESC'; // Contient la direction DESC ou ASC
    public ?DateTime $updatedAtStart = null; // Peut être 
    public ?DateTime $updatedAtStop = null;
}
