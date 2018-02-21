<?php
/**
 *    Copyright 2015-2018 Mathieu Piot.
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace AppBundle\Twig;

use Symfony\Component\Routing\RouterInterface;

class NeighborhoodExtention extends \Twig_Extension
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('drawNeighborhood', [$this, 'drawNeighborhood']),
        ];
    }

    public function drawNeighborhood($neighborhood)
    {
        // Some var to config the svg
        $height = 80;
        $width = 1000;
        $paddingV = 20;
        $paddingH = 20;

        // Arrow color and size
        $arrowLineWidth = 10;
        $arrowWidth = 14;
        $arrowLength = 10;
        $arrowFill = '#FFBBBB';
        $arrowStroke = '#DD2222';
        $arrowStrokeWidth = 2;

        // Baseline color and size
        $baseLineStroke = 'black';
        $baseLineStrokeWidth = 1;

        // About Locus and intergenes
        $expectedNbLocus = $neighborhood->first()->getNumberNeighbours() * 2 + 1;
        $expectedNbIntergenes = $expectedNbLocus - 1;
        $intergeneRatio = 0.5;

        // Define Locus and intergenes length
        $locusSize = $width / ($expectedNbLocus + ($expectedNbIntergenes * $intergeneRatio));
        $intergeneSize = $locusSize * $intergeneRatio;

        // Create the SVG
        $svg = '<svg height="'.($height + $paddingV).'" width="'.($width + $paddingH).'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1">';

        // Add styles
        $svg .= '<style>a { cursor: pointer; }</style>';

        // Add the plasmid line
        $svg .= '<line x1="'.($paddingH / 2).'" y1="'.($height / 2).'" x2="'.($width + $paddingH / 2).'" y2="'.($height / 2).'" style="stroke: '.$baseLineStroke.';stroke-width: '.$baseLineStrokeWidth.'" />';

        // Foreach neighbour
        foreach ($neighborhood as $key => $neighbour) {
            $neighbourLocus = $neighbour->getNeighbour();
            $i = $neighbour->getPosition() + $neighbour->getNumberNeighbours();

            $x1 = $i * ($locusSize + $intergeneSize) + $paddingH / 2;
            $x2 = $x1 + $locusSize + $paddingH / 2;

            // Add a link to the locus
            $locusUrl = $this->router->generate('locus_view', [
                'species_slug' => $neighbourLocus->getChromosome()->getStrain()->getSpecies()->getSlug(),
                'strain_slug' => $neighbourLocus->getChromosome()->getStrain()->getSlug(),
                'chromosome_slug' => $neighbourLocus->getChromosome()->getSlug(),
                'locus_slug' => $neighbourLocus->getSlug(),
            ]);
            $svg .= '<a xlink:href="'.$locusUrl.'">';

            // If strand sens
            if (1 === $neighbour->getNeighbour()->getStrand()) {
                $svg .= '<path
                    d=" M '.$x1.' '.($height / 2 - $arrowLineWidth / 2).'
                        L '.($x2 - $arrowLength).' '.($height / 2 - $arrowLineWidth / 2).'
                        L '.($x2 - $arrowLength).' '.($height / 2 - $arrowLineWidth / 2 - ($arrowWidth - $arrowLineWidth) / 2).'
                        L '.$x2.' '.($height / 2).'
                        L '.($x2 - $arrowLength).' '.($height / 2 + $arrowLineWidth / 2 + ($arrowWidth - $arrowLineWidth) / 2).'
                        L '.($x2 - $arrowLength).' '.($height / 2 + $arrowLineWidth / 2).'
                        L '.$x1.' '.($height / 2 + $arrowLineWidth / 2).'
                        Z
                        "';
            }
            // Else, strand anti-sens
            else {
                $svg .= '<path
                    d=" M '.$x2.' '.($height / 2 - $arrowLineWidth / 2).'
                        L '.($x1 + $arrowLength).' '.($height / 2 - $arrowLineWidth / 2).'
                        L '.($x1 + $arrowLength).' '.($height / 2 - $arrowLineWidth / 2 - ($arrowWidth - $arrowLineWidth) / 2).'
                        L '.$x1.' '.($height / 2).'
                        L '.($x1 + $arrowLength).' '.($height / 2 + $arrowLineWidth / 2 + ($arrowWidth - $arrowLineWidth) / 2).'
                        L '.($x1 + $arrowLength).' '.($height / 2 + $arrowLineWidth / 2).'
                        L '.$x2.' '.($height / 2 + $arrowLineWidth / 2).'
                        Z
                        "';
            }

            // Finish arrow
            $svg .= 'data-toggle="tooltip" data-html="true" title="<u>Size:</u> '.($neighbourLocus->getEnd() - $neighbourLocus->getStart()).' bp"
                    stroke="'.$arrowStroke.'"
                    stroke-width="'.$arrowStrokeWidth.'"px
                    fill="'.$arrowFill.'" />';

            // Add name and positions
            $svg .= '<text x="'.(($x1 + $x2) / 2).'" y="'.($height / 2 + $arrowWidth + 10).'" style="text-anchor: middle;">'.$neighbourLocus->getName().'</text>';
            $svg .= '<text x="'.(($x1 + $x2) / 2).'" y="'.($height / 2 + $arrowWidth + 30).'" style="text-anchor: middle;">'.$neighbourLocus->getStart().'..'.$neighbourLocus->getEnd().'</text>';
            $svg .= '</a>';

            // Add the inter-gene
            $nextNeightbour = $neighborhood->get($key + 1);
            if (null !== $nextNeightbour) {
                $x1 = $x2;
                $x2 = $x1 + $intergeneSize - $arrowLength - $arrowStrokeWidth;
                $width = $x2 - $x1;

                // Define the intergene
                $intergene = $nextNeightbour->getNeighbour()->getStart() - $neighbour->getNeighbour()->getEnd() - 1;
                $title = $intergene < 0 ? '<u>Intergene:</u> overlap ('.abs($intergene).' bp)' : '<u>Intergene:</u> '.$intergene.' bp';

                $svg .= '<rect x="'.$x1.'" y="'.($height / 2 - $arrowWidth / 2).'" width="'.$width.'" height="'.$arrowWidth.'" fill="transparent" data-toggle="tooltip" data-html="true" title="'.$title.'"/>';
            }
        }

        // Close and return the svg
        $svg .= '</svg>';
        echo  $svg;
    }

    public function getName()
    {
        return 'neighborhood_extension';
    }
}
