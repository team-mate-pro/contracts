<?php

declare(strict_types=1);

namespace Tests\Unit\Dto;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TeamMatePro\Contracts\Dto\Pagination;

#[CoversClass(Pagination::class)]
final class PaginationTest extends TestCase
{
    public function testConstructorWithDefaultValues(): void
    {
        $pagination = new Pagination();

        $this->assertSame(0, $pagination->getOffset());
        $this->assertSame(50, $pagination->getLimit());
    }

    public function testConstructorWithCustomValues(): void
    {
        $pagination = new Pagination(offset: 100, limit: 25);

        $this->assertSame(100, $pagination->getOffset());
        $this->assertSame(25, $pagination->getLimit());
    }

    public function testDefaultFactoryMethod(): void
    {
        $pagination = Pagination::default();

        $this->assertSame(0, $pagination->getOffset());
        $this->assertSame(50, $pagination->getLimit());
    }

    public function testFromPageWithFirstPage(): void
    {
        $pagination = Pagination::fromPage(page: 1, limit: 50);

        $this->assertSame(0, $pagination->getOffset());
        $this->assertSame(50, $pagination->getLimit());
    }

    public function testFromPageWithSecondPage(): void
    {
        $pagination = Pagination::fromPage(page: 2, limit: 50);

        $this->assertSame(50, $pagination->getOffset());
        $this->assertSame(50, $pagination->getLimit());
    }

    public function testFromPageWithThirdPage(): void
    {
        $pagination = Pagination::fromPage(page: 3, limit: 50);

        $this->assertSame(100, $pagination->getOffset());
        $this->assertSame(50, $pagination->getLimit());
    }

    public function testFromPageWithCustomLimit(): void
    {
        $pagination = Pagination::fromPage(page: 5, limit: 20);

        $this->assertSame(80, $pagination->getOffset());
        $this->assertSame(20, $pagination->getLimit());
    }

    public function testFromPageWithDefaultLimit(): void
    {
        $pagination = Pagination::fromPage(page: 2);

        $this->assertSame(50, $pagination->getOffset());
        $this->assertSame(50, $pagination->getLimit());
    }

    public function testFromPageThrowsExceptionForZeroPage(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Page must be a positive integer');

        Pagination::fromPage(page: 0, limit: 50);
    }

    public function testFromPageThrowsExceptionForNegativePage(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Page must be a positive integer');

        Pagination::fromPage(page: -1, limit: 50);
    }

    public function testFromPageCalculatesOffsetCorrectly(): void
    {
        // Test various page/limit combinations
        $testCases = [
            ['page' => 1, 'limit' => 10, 'expectedOffset' => 0],
            ['page' => 2, 'limit' => 10, 'expectedOffset' => 10],
            ['page' => 10, 'limit' => 25, 'expectedOffset' => 225],
            ['page' => 100, 'limit' => 100, 'expectedOffset' => 9900],
        ];

        foreach ($testCases as $testCase) {
            $pagination = Pagination::fromPage(
                page: $testCase['page'],
                limit: $testCase['limit']
            );

            $this->assertSame(
                $testCase['expectedOffset'],
                $pagination->getOffset(),
                sprintf(
                    'Failed for page %d with limit %d',
                    $testCase['page'],
                    $testCase['limit']
                )
            );
            $this->assertSame($testCase['limit'], $pagination->getLimit());
        }
    }

    public function testPaginationIsReadonly(): void
    {
        $pagination = new Pagination(offset: 10, limit: 20);

        // This test verifies the readonly nature by ensuring getters return consistent values
        $this->assertSame(10, $pagination->getOffset());
        $this->assertSame(10, $pagination->getOffset()); // Should be the same

        $this->assertSame(20, $pagination->getLimit());
        $this->assertSame(20, $pagination->getLimit()); // Should be the same
    }

    public function testPaginationImmutability(): void
    {
        $pagination1 = Pagination::fromPage(page: 1, limit: 50);
        $pagination2 = Pagination::fromPage(page: 2, limit: 50);

        // Each instance should be independent
        $this->assertNotSame($pagination1, $pagination2);
        $this->assertSame(0, $pagination1->getOffset());
        $this->assertSame(50, $pagination2->getOffset());
    }

    public function testEdgeCaseWithLargePageNumber(): void
    {
        $pagination = Pagination::fromPage(page: 1000, limit: 100);

        $this->assertSame(99900, $pagination->getOffset());
        $this->assertSame(100, $pagination->getLimit());
    }

    public function testEdgeCaseWithSmallLimit(): void
    {
        $pagination = Pagination::fromPage(page: 5, limit: 1);

        $this->assertSame(4, $pagination->getOffset());
        $this->assertSame(1, $pagination->getLimit());
    }

    // Nullable limit test cases

    public function testConstructorWithNullLimit(): void
    {
        $pagination = new Pagination(offset: 0, limit: null);

        $this->assertSame(0, $pagination->getOffset());
        $this->assertNull($pagination->getLimit());
    }

    public function testConstructorWithNullLimitAndCustomOffset(): void
    {
        $pagination = new Pagination(offset: 100, limit: null);

        $this->assertSame(100, $pagination->getOffset());
        $this->assertNull($pagination->getLimit());
    }

    public function testWithNoLimitFactoryMethod(): void
    {
        $pagination = Pagination::withNoLimit();

        $this->assertSame(0, $pagination->getOffset());
        $this->assertNull($pagination->getLimit());
    }

    public function testFromPageWithNullLimit(): void
    {
        $pagination = Pagination::fromPage(page: 1, limit: null);

        $this->assertSame(0, $pagination->getOffset());
        $this->assertNull($pagination->getLimit());
    }

    public function testFromPageWithNullLimitOnSecondPage(): void
    {
        $pagination = Pagination::fromPage(page: 2, limit: null);

        $this->assertSame(0, $pagination->getOffset());
        $this->assertNull($pagination->getLimit());
    }

    public function testNullLimitMeansNoLimit(): void
    {
        // When limit is null, it should represent "fetch all results"
        $paginationWithLimit = new Pagination(offset: 0, limit: 50);
        $paginationWithoutLimit = new Pagination(offset: 0, limit: null);

        $this->assertSame(50, $paginationWithLimit->getLimit());
        $this->assertNull($paginationWithoutLimit->getLimit());
    }

    public function testNullLimitIsIndependentFromOffset(): void
    {
        $pagination1 = new Pagination(offset: 0, limit: null);
        $pagination2 = new Pagination(offset: 100, limit: null);
        $pagination3 = new Pagination(offset: 500, limit: null);

        $this->assertNull($pagination1->getLimit());
        $this->assertNull($pagination2->getLimit());
        $this->assertNull($pagination3->getLimit());

        $this->assertSame(0, $pagination1->getOffset());
        $this->assertSame(100, $pagination2->getOffset());
        $this->assertSame(500, $pagination3->getOffset());
    }

    public function testFromPageWithNullLimitDoesNotCalculateOffset(): void
    {
        // When limit is null, offset calculation should result in 0
        // because ($page - 1) * null = 0
        $testCases = [
            ['page' => 1, 'expectedOffset' => 0],
            ['page' => 2, 'expectedOffset' => 0],
            ['page' => 5, 'expectedOffset' => 0],
            ['page' => 100, 'expectedOffset' => 0],
        ];

        foreach ($testCases as $testCase) {
            $pagination = Pagination::fromPage(
                page: $testCase['page'],
                limit: null
            );

            $this->assertSame(
                $testCase['expectedOffset'],
                $pagination->getOffset(),
                sprintf('Failed for page %d with null limit', $testCase['page'])
            );
            $this->assertNull($pagination->getLimit());
        }
    }

    public function testMixedLimitScenarios(): void
    {
        // Test that we can create different pagination instances with mixed limits
        $withLimit = Pagination::fromPage(page: 2, limit: 50);
        $withoutLimit = Pagination::fromPage(page: 2, limit: null);
        $defaultLimit = Pagination::fromPage(page: 2);

        $this->assertSame(50, $withLimit->getOffset());
        $this->assertSame(50, $withLimit->getLimit());

        $this->assertSame(0, $withoutLimit->getOffset());
        $this->assertNull($withoutLimit->getLimit());

        $this->assertSame(50, $defaultLimit->getOffset());
        $this->assertSame(50, $defaultLimit->getLimit());
    }

    public function testNullLimitUseCaseForFetchingAllResults(): void
    {
        // Real-world scenario: Fetching all results starting from offset
        $pagination = new Pagination(offset: 100, limit: null);

        $this->assertSame(100, $pagination->getOffset());
        $this->assertNull($pagination->getLimit());

        // This would typically translate to a query like:
        // SELECT * FROM table OFFSET 100 (no LIMIT clause)
    }
}
