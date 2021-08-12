<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

trait TraitAdminOnly
{
    /**
     * Flag to indicate you want admin-only locations included in your
     * request.
     */
    public ?bool $admin_only = null;

    /**
     * Set to indicate you want admin-only categories included in your request.
     *
     * @param bool $admin_only Value to set, enabling by default.
     * @return self A reference to this object for method chaining.
     */
    final public function setAdminOnly(bool $admin_only = true): self
    {
        $this->admin_only = $admin_only;

        return $this;
    }

    /**
     * Add admin_only query param to a URI.
     *
     * @param string $uri The URI to add query param to.
     * @return string The URI with added query param.
     */
    final protected function addAdminOnly(string $uri): string
    {
        return self::addQuery($uri, 'admin_only', $this->admin_only);
    }
}
