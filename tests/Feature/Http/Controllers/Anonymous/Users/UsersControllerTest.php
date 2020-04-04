<?php

namespace Tests\Feature\Http\Controllers\Anonymous\Users;

use Tests\OAuthTestCaseTrait;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersControllerTest extends TestCase
{
    use OAuthTestCaseTrait;
    use DatabaseMigrations;

    public function testToVisitDashboard()
    {
        $this
            ->get('/')
            ->assertSuccessful()
            ->assertSeeText('A Laravel Framework template for web application.')
            ->assertSeeText('Home')
            ->assertSeeText('Contact')
            ->assertSeeText('Terms of Service')
            ->assertSeeText('Login');
    }

    public function testToVisitDashboardInFrench()
    {
        $this
            ->get('/?locale=fr')
            ->assertSuccessful()
            ->assertSeeText('A Laravel Framework template for web application.')
            ->assertSeeText('Accueil')
            ->assertSeeText('Contact')
            ->assertSeeText(e('Conditions générales d\'utilisation'))
            ->assertSeeText('Se connecter');
    }

    public function testToVisitDashboardInGerman()
    {
        $this
            ->get('/?locale=de')
            ->assertSuccessful()
            ->assertSeeText('A Laravel Framework template for web application.')
            ->assertSeeText('Home')
            ->assertSeeText('Contact')
            ->assertSeeText('Terms of Service')
            ->assertSeeText('Einloggen');
    }

    public function testToVisitDashboardInSpanish()
    {
        $this
            ->get('/?locale=es')
            ->assertSuccessful()
            ->assertSeeText('A Laravel Framework template for web application.')
            ->assertSeeText('Home')
            ->assertSeeText('Contact')
            ->assertSeeText('Terms of Service')
            ->assertSeeText('Iniciar sesión');
    }

    public function testToVisitDashboardInRussian()
    {
        $this
            ->get('/?locale=ru')
            ->assertSuccessful()
            ->assertSeeText('A Laravel Framework template for web application.')
            ->assertSeeText('Home')
            ->assertSeeText('Contact')
            ->assertSeeText('Terms of Service')
            ->assertSeeText('Авторизоваться');
    }

    public function testToVisitDashboardInChinese()
    {
        $this
            ->get('/?locale=zh-CN')
            ->assertSuccessful()
            ->assertSeeText('A Laravel Framework template for web application.')
            ->assertSeeText('Home')
            ->assertSeeText('Contact')
            ->assertSeeText('Terms of Service')
            ->assertSeeText('登录');
    }

    public function testToVisitTerms()
    {
        $this
            ->get('/terms-of-services')
            ->assertSuccessful()
            ->assertSeeText('template.benevaut.tech is a demonstration website');
    }

    public function testToVisitTermsInFrench()
    {
        $this
            ->get('/terms-of-services?locale=fr')
            ->assertSuccessful()
            ->assertSeeText('template.benevaut.tech est une plateforme de démonstration.');
    }
}
