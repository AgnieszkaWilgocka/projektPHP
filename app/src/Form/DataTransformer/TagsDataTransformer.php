<?php
/**
 * Tags data transformer.
 */
namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsDataTransformer
 */
class TagsDataTransformer implements DataTransformerInterface
{
    private $tagsRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagsRepository = $tagRepository;
    }

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

    public function reverseTransform($value)
    {
        $tagTitles = explode(',', $value);
        $tags = [];

        foreach ($tagTitles as $tagTitle) {
            if ('' !== trim($tagTitle)) {
                $tag = $this->tagsRepository->findOneByTitle(strtolower($tagTitle));

                if ($tag == null) {
                    $tag = new Tag();
                    $tag->setTitle($tagTitle);
                    $this->tagsRepository->save($tag);
                }
                $tags[] = $tag;
            }
        }
        return $tags;
    }


}