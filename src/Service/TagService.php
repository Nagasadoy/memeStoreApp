<?php

namespace App\Service;

use App\Entity\Tag\DTO\EditTagDTO;
use App\Entity\Tag\Tag;
use App\Entity\User\User;
use App\Repository\TagRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class TagService
{

    public function __construct(private readonly TagRepository $tagRepository)
    {
    }

    public function create(string $name, User $user): Tag
    {
        $tag = new Tag($name, $user);
        $this->tagRepository->save($tag, true);

        return $tag;
    }

    public function edit(EditTagDTO $editTagDTO): Tag
    {
        $tag = $this->tagRepository->find($editTagDTO->getId());

        if (null === $tag) {
            throw new \DomainException('Не найдено тэга с таким id');
        }

        $tag->setName($editTagDTO->getName());
        $this->tagRepository->save($tag, true);

        return $tag;
    }
}
