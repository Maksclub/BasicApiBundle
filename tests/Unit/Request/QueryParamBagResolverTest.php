<?php declare(strict_types=1);

namespace Condenast\BasicApiBundle\Tests\Unit\Request;

use Condenast\BasicApiBundle\Request\QueryParamBag;
use Condenast\BasicApiBundle\Request\QueryParamBagResolver;
use Condenast\BasicApiBundle\Tests\Unit\ObjectMother;
use PHPUnit\Framework\TestCase;

class QueryParamBagResolverTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_initialize_and_resolve_the_query_param_bag_argument(): void
    {
        $value = 'value';
        $queryParam = ObjectMother::queryParam();
        $request = ObjectMother::queryParamsRequest(
            [$queryParam],
            [$queryParam->getPath() => $value]
        );

        $resolver = new QueryParamBagResolver(ObjectMother::propertyAccessor(), ObjectMother::validator());

        $bag = \current($resolver->resolve($request, ObjectMother::argumentMetadata()));

        self::assertInstanceOf(QueryParamBag::class, $bag);
        self::assertSame($value, $bag->get($queryParam->getName()));
    }

    /**
     * @test
     */
    public function it_initializes_with_a_default_value_if_the_parameter_is_not_present_in_the_request(): void
    {
        $queryParam = ObjectMother::queryParam();
        $request = ObjectMother::queryParamsRequest([$queryParam]);

        $resolver = new QueryParamBagResolver(ObjectMother::propertyAccessor(), ObjectMother::validator());

        $bag = \current($resolver->resolve($request, ObjectMother::argumentMetadata()));

        self::assertSame($queryParam->getDefault(), $bag->get($queryParam->getName()));
    }
}
