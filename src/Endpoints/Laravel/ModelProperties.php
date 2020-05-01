<?php

namespace PHPFileManipulator\Endpoints\Laravel;

use PHPFileManipulator\Endpoints\EndpointProvider;

class ModelProperties extends EndpointProvider
{
    public function getHandlerMethod($signature, $args)
    {
        return collect($this->methods)->contains($signature) ? 'fromTemplate' : false;
    }

    public function getEndpoints()
    {
        return $this->methods;
    }

    public function __call($method, $args)
    {
        return "Attribute handle method $method not implemented yet";
    }

    protected $methods = [
        "app",
        "publishes",
        "publishGroups",
        "policies",
        //"namespace",
        "listen",
        "subscribe",
        //"fillable",
        //"hidden",
        "casts",
        "connection",
        "table",
        "primaryKey",
        "keyType",
        "incrementing",
        "with",
        "withCount",
        "perPage",
        "exists",
        "wasRecentlyCreated",
        "resolver",
        "dispatcher",
        "booted",
        "traitInitializers",
        "globalScopes",
        "ignoreOnTouch",
        "attributes",
        "original",
        "changes",
        "classCastCache",
        "primitiveCastTypes",
        "dates",
        "dateFormat",
        "appends",
        "snakeAttributes",
        "mutatorCache",
        "dispatchesEvents",
        "observables",
        "relations",
        "touches",
        "manyMethods",
        "timestamps",
        "visible",
        "guarded",
        "unguarded",
        "rememberTokenName",
        "dontReport",
        "dontFlash",
        "container",
        "internalDontReport",
        "except",
        "encrypter",
        "addHttpCookie",
        "auth",
        "proxies",
        "headers",
        "config",
        "serialize",
        "middleware",
        "middlewareGroups",
        "routeMiddleware",
        "router",
        "bootstrappers",
        "middlewarePriority",
        "commands",
        "events",
        "artisan",
        "commandsLoaded",
        "backupGlobals",
        "backupGlobalsBlacklist",
        "backupStaticAttributes",
        "backupStaticAttributesBlacklist",
        "runTestInSeparateProcess",
        "preserveGlobalState",
        "afterApplicationCreatedCallbacks",
        "beforeApplicationDestroyedCallbacks",
        "callbackException",
        "setUpHasRun",
        "originalMix",
        "defaultHeaders",
        "defaultCookies",
        "unencryptedCookies",
        "serverVariables",
        "followRedirects",
        "encryptCookies",
        "mockConsoleOutput",
        "expectedOutput",
        "expectedQuestions",
        "originalExceptionHandler",
        "firedEvents",
        "firedModelEvents",
        "dispatchedJobs",
        "dispatchedNotifications",
    ];
}