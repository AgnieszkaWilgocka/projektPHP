<?php
/**
 * Tags data transformer.
 */
namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Service\TagService;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsDataTransformer
 */
class TagsDataTransformer implements DataTransformerInterface
{
    private $tagsService;

    /**
     * TagsDataTransformer constructor.
     *
     * @param TagService $tagService
     */
    public function __construct(TagService $tagService)
    {
        $this->tagsService = $tagService;
    }

    /**
     * @param mixed $tags
     *
     * @return string
     */
    public function transform($tags): string
    {
        if (null == $tags) {
            return '';
        }

        $tagTitles = [];

        foreach ($tags as $tag) {
            $tagTitles[] = $tag->getTitle();
        }

        return implode(',', $tagTitles);
    }

    /**
     * @param mixed $value
     *
     * @return array|mixed
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reverseTransform($value)
    {
        $tagTitles = explode(',', $value);
        $tags = [];

        foreach ($tagTitles as $tagTitle) {
            if ('' !== trim($tagTitle)) {
                $tag = $this->tagsService->findOneByTitle(strtolower($tagTitle));

                if ($tag == null) {
                    $tag = new Tag();
                    $tag->setTitle($tagTitle);
                    $this->tagsService->save($tag);
                }
                $tags[] = $tag;
            }
        }

        return $tags;
    }
}
