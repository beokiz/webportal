<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Http\Traits;

/**
 * Controller Trait
 *
 * @package \App\Http\Traits
 */
trait ControllerTrait
{
    /**
     * @param mixed $domains
     * @return mixed
     */
    protected function prepareDomainsData($domains)
    {
        return $domains->transform(function ($domain, $domainIndex) {
            $milestoneIndex = 0;

            $domain->subdomains = $domain->subdomains->transform(function ($subdomain) use ($domainIndex, &$milestoneIndex) {
                $subdomain->milestones = $subdomain->milestones->transform(function ($milestone) use ($domainIndex, &$milestoneIndex) {
                    $milestone->index        = $milestoneIndex++;
                    $milestone->domain_index = $domainIndex;

                    return $milestone;
                });

                return $subdomain;
            });

            return $domain;
        });
    }
}
