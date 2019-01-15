<?php
    /**
     * To add some image sizes.
     */

CONST IMAGES_SIZES = [
/*     1 => ['image_1600px', 1600, 1067],
    2 => ['image_1600px_retina', 3200, 2134],
    3 => ['image_1140px', 1140, 760],
    4 => ['image_1140px_retina', 2280, 1521] */
];

foreach (IMAGES_SIZES as $image) {
    add_image_size($image[0], $image[1], $image[2], ['center', 'top']);
}
