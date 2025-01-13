<?php

namespace App\Entity;

enum ProductStatus: string
{
    case IN_STOCK = 'in_stock';
    case OUT_OF_STOCK = 'out_of_stock';
    case DISCONTINUED = 'discontinued';
}
