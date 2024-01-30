<?php

namespace HeaderX\JetstreamPassport\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;

class ApiTokenManager extends Component
{
    /**
     * The create API token form state.
     *
     * @var array
     */
    public $createApiTokenForm = [
        'name' => '',
        'scopes' => [],
    ];

    /**
     * Indicates if the plain text token is being displayed to the user.
     *
     * @var bool
     */
    public $displayingToken = false;

    /**
     * The plain text token value.
     *
     * @var string|null
     */
    public $plainTextToken;

    /**
     * Indicates if the user is currently managing an API token's permissions.
     *
     * @var bool
     */
    public $managingApiTokenPermissions = false;

    /**
     * The token that is currently having its permissions managed.
     *
     * @var Laravel\Passport\Token|null
     */
    public $managingPermissionsForId;

    /**
     * The update API token form state.
     *
     * @var array
     */
    public $updateApiTokenForm = [
        'scopes' => [],
    ];

    /**
     * Indicates if the application is confirming if an API token should be deleted.
     *
     * @var bool
     */
    public $confirmingApiTokenDeletion = false;

    /**
     * The ID of the API token being deleted.
     *
     * @var string
     */
    public $apiTokenIdBeingDeleted;

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount()
    {
        $this->createApiTokenForm['scopes'] = Jetstream::$defaultPermissions;
    }

    /**
     * Create a new API token.
     *
     * @return void
     */
    public function createApiToken()
    {
        $this->resetErrorBag();

        Validator::make([
            'name' => $this->createApiTokenForm['name'],
        ], [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createApiToken');

        $this->displayTokenValue(($this->user->createToken(
            $this->createApiTokenForm['name'],
            Jetstream::validPermissions($this->createApiTokenForm['scopes'])
        )->accessToken));

        $this->createApiTokenForm['name'] = '';
        $this->createApiTokenForm['scopes'] = Jetstream::$defaultPermissions;

        $this->dispatch('created');
    }

    /**
     * Display the token value to the user.
     *
     * @param  \Laravel\Passport\Token  $token
     * @return void
     */
    protected function displayTokenValue($token)
    {
        $this->displayingToken = true;

        $this->plainTextToken = $token;

        $this->dispatch('showing-token-modal');
    }

    /**
     * Allow the given token's permissions to be managed.
     *
     * @param  int  $tokenId
     * @return void
     */
    public function manageApiTokenPermissions($tokenId)
    {
        $this->managingApiTokenPermissions = true;

        $token = $this->user->tokens()->where(
            'id',
            $tokenId
        )->firstOrFail();

        $this->managingPermissionsForId = $token->id;

        $this->updateApiTokenForm['scopes'] = $token->scopes;
    }

    /**
     * Update the API token's permissions.
     *
     * @return void
     */
    public function updateApiToken()
    {
        $token = $this->user->tokens()->where(
            'id',
            $this->managingPermissionsForId
        )->firstOrFail();

        $token->forceFill([
            'scopes' => Jetstream::validPermissions($this->updateApiTokenForm['scopes']),
        ])->save();

        $this->managingApiTokenPermissions = false;
    }

    /**
     * Confirm that the given API token should be deleted.
     *
     * @param  int  $tokenId
     * @return void
     */
    public function confirmApiTokenDeletion($tokenId)
    {
        $this->confirmingApiTokenDeletion = true;

        $this->apiTokenIdBeingDeleted = $tokenId;
    }

    /**
     * Delete the API token.
     *
     * @return void
     */
    public function deleteApiToken()
    {
        $this->user->tokens()->where('id', $this->apiTokenIdBeingDeleted)->delete();

        $this->user->load('tokens');

        $this->confirmingApiTokenDeletion = false;

        $this->managingPermissionsForId = null;
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('jetstream-passport::api-token-manager');
    }
}
