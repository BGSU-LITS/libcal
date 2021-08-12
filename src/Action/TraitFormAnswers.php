<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

trait TraitFormAnswers
{
    /** Indicates if you want custom form answers to be returned. */
    public ?bool $formAnswers = null;

    /**
     * Set to indicate if you want custom form answers to be returned.
     *
     * @param bool $formAnswers Value to set, enabling by default.
     * @return self A reference to this object for method chaining.
     */
    final public function setFormAnswers(bool $formAnswers = true): self
    {
        $this->formAnswers = $formAnswers;

        return $this;
    }

    /**
     * Add "formAnswers" query param to a URI.
     *
     * @param string $uri The URI to add query param to.
     * @return string The URI with added query param.
     */
    final protected function addFormAnswers(string $uri): string
    {
        return self::addQuery($uri, 'formAnswers', $this->formAnswers);
    }
}
