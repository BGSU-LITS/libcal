<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

trait TraitAdminOnly
{
    /** Include admin-only results in your request. */
    public ?bool $admin_only = null;

    /**
     * Set to include admin-only results in your request.
     *
     * @param bool $admin_only Value to set, enabling by default.
     * @return self Return self for chaining.
     */
    public function setAdminOnly(bool $admin_only = true): self
    {
        $this->admin_only = $admin_only;

        return $this;
    }

    /**
     * Add admin_only param to a URI.
     *
     * @param string $uri The URI to add admin_only query to.
     * @return string The URI with added admin_only query.
     */
    protected function addAdminOnly(string $uri): string
    {
        return self::addQuery($uri, 'admin_only', $this->admin_only);
    }
}
