<?php

namespace Azuriom\Extensions\Plugin;

abstract class UserProfileCardComposer extends AdminDashboardCardComposer
{
    /**
     * Get the cards to add to the user profile.
     * Each card should contain:
     * - 'name' : The title of the card
     * - 'view' : The view (Ex: shop::giftcards.index).
     *
     * @return array{name: string, view: string}[]
     */
    abstract public function getCards();
}
