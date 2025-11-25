<?php

declare(strict_types=1);

namespace App\Tests\Application\HAL;

use App\Application\HAL\LinkPopulator;
use PHPUnit\Framework\TestCase;

final class LinkPopulatorTest extends TestCase
{
    public function testPopulateRestLinksShouldSetLinksForObjectAndChildren(): void
    {
        // Arrange
        $resource = new StubResource(new StubResource());
        $sut = new LinkPopulator();
        // Act
        $sut->populateRestLinks($resource);
        // Assert
        $this->assertNotNull($resource->halLinks(), 'REST links of parent not set!');
        $this->assertNotNull($resource->child->halLinks(), 'REST links of child not set!');
    }

    public function testPopulateWebLinksShouldSetLinksForObjectAndChildren(): void
    {
        // Arrange
        $resource = new StubResource(new StubResource());
        $sut = new LinkPopulator();
        // Act
        $sut->populateWebLinks($resource);
        // Assert
        $this->assertNotNull($resource->halLinks(), 'Web links of parent not set!');
        $this->assertNotNull($resource->child->halLinks(), 'Web links of child not set!');
    }

    public function testPopulateRestLinksShouldHandleCyclicalReferences(): void
    {
        // Arrange
        $resource = new StubResource(new StubResource());
        $resource = new StubResource($resource);
        $sut = new LinkPopulator();
        // Act
        $sut->populateRestLinks($resource);
        // Assert
        $this->assertNotNull($resource->halLinks(), 'REST links of parent not set!');
        $this->assertNotNull($resource->child->halLinks(), 'REST links of child not set!');
    }

    public function testPopulateWebLinksShouldHandleCyclicalReferences(): void
    {
        // Arrange
        $resource = new StubResource(new StubResource());
        $resource = new StubResource($resource);
        $sut = new LinkPopulator();
        // Act
        $sut->populateWebLinks($resource);
        // Assert
        $this->assertNotNull($resource->halLinks(), 'Web links of parent not set!');
        $this->assertNotNull($resource->child->halLinks(), 'Web links of child not set!');
    }

    public function testPopulateRestLinksShouldSetLinksForArrays(): void
    {
        // Arrange
        $resources = new StubResourceArray(new StubResource(), new StubResource(), new StubResource());
        $sut = new LinkPopulator();
        // Act
        $sut->populateRestLinks($resources);
        // Assert
        $this->assertNotNull($resources->halLinks(), 'Rest links of array resource not set!');
        foreach ($resources as $key => $resource) {
            $this->assertNotNull($resource->halLinks(), "Rest links of element $key not set!");
        }
    }

    public function testPopulateWebLinksShouldSetLinksForArrays(): void
    {
        // Arrange
        $resources = new StubResourceArray(new StubResource(), new StubResource(), new StubResource());
        $sut = new LinkPopulator();
        // Act
        $sut->populateWebLinks($resources);
        // Assert
        $this->assertNotNull($resources->halLinks(), 'Web links of array resource not set!');
        foreach ($resources as $key => $resource) {
            $this->assertNotNull($resource->halLinks(), "Web links of element $key not set!");
        }
    }
}
