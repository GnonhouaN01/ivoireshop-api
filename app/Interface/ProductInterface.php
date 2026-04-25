<?php
namespace App\Interfaces;
use App\Models\Product;

interface ProductInterface
{
    public function hasDiscount(): bool; // has_discount permet de vérifier si le produit a une promotion active
    public function getDiscountPercentAttribute(): int; // get_discount_percent calcule le pourcentage de réduction basé sur le prix original et le prix promotionnel
    public function getThumbnailAttribute(): ?string; // get_thumbnail retourne l'URL de la première image du produit ou une image par défaut si aucune n'est disponible
    public function isLowStock(): bool; // is_low_stock vérifie si le stock du produit est inférieur ou égal à un seuil défini (par exemple, 5)
    public function isOutOfStock(): bool; // is_out_of_stock vérifie si le stock du produit est égal à zéro
}
