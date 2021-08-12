<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

trait TraitCategoryId
{
    /** A category ID to only show spaces from this category. */
    public ?int $categoryId = null;

    /**
     * Set a category ID to only show spaces from this category.
     *
     * @param int $categoryId Value to set.
     * @return self A reference to this object for method chaining.
     */
    final public function setCategoryId(int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Add categoryId param to a URI.
     *
     * @param string $uri The URI to add query param to.
     * @return string The URI with added query param.
     */
    final protected function addCategoryId(string $uri): string
    {
        return self::addQuery($uri, 'categoryId', $this->categoryId);
    }
}
