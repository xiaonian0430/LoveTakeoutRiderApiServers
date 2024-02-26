<?php


declare(strict_types=1);
/**
 * JWT 中间件
 * @author xiaonian
 * @date 2024-02-18
 */

namespace App\Middleware;
use App\Constants\MessageCode;
use App\Service\Jwt\JwtService;
use App\Service\Member\SsoService;
use Hyperf\Context\Context;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Container\ContainerInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function Hyperf\Support\value;

class JwtAuthMiddleware implements MiddlewareInterface
{
    protected string $prefix = '/Bearer';
    protected string $key = 'Authorization';

    protected ContainerInterface $container;

    protected RequestInterface $request;

    protected ResponseInterface $response;

    public function __construct(ContainerInterface $container, ResponseInterface $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): PsrResponseInterface
    {
        $token = $this->request->header($this->key);
        //过滤掉$this->prefix
        if ($token && preg_match($this->prefix . '\s*(\S+)\b/i', $token, $matches)) {
            $token = $matches[1];
        }
        $jwtResult=(new JwtService())->decodeJwt($token);
        try{
            $riderId=$jwtResult->data->rider_id;
        }catch (\Throwable $e){
            $riderId=0;
        }

        //验证token是否有效
        //验证登录信息
        $tokenOnServer=(new SsoService())->getSsoToken($riderId);
        if($tokenOnServer==$token && !empty($token)){
            Context::set('rider_id', $riderId);
            return $handler->handle($request);
        }else{
            $data=[
                "message"=>MessageCode::getMessage(MessageCode::NO_LOGIN),
                "code"=>MessageCode::NO_LOGIN,
                "data"=>[
                    "login"=>0,
                ]
            ];
            return $this->response->json($data)->withStatus(MessageCode::NO_LOGIN);
        }
    }
}