<?php

namespace HeaderX\JetstreamPassport\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\ClientRepository;
use Livewire\Component;

class OAuthClientManager extends Component
{
    /**
     * The create form state.
     *
     * @var array
     */
    public $createForm = [
        'name' => '',
        'redirect' => '',
        'confidential' => true,
    ];

    /**
     * The the id of the client currently being managed.
     *
     * @var \Laravel\Passport\ClientRepository|null
     */
    public $managingClientId;

    /**
     * The the id of the newly created client.
     *
     * @var string
     */
    public $newClientId;

    /**
     * The update form state.
     *
     * @var array
     */
    public $updateForm = [
        'name' => '',
        'redirect' => '',
        'confidential' => true,
    ];

    /**
     * Indicates if the application is confirming if an API token should be deleted.
     *
     * @var bool
     */
    public $confirmingDeletion = false;

    /**
     * Indicates if the application is managing a client.
     *
     * @var bool
     */
    public $managingClient = false;

    /**
     * Indicates if the application is displaying the client secret.
     *
     * @var bool
     */
    public $displayingSecret = false;

    /**
     * The ID of the client being deleted.
     *
     * @var string
     */
    public $clientIdBeingDeleted;

    /**
     * The Client Secret.
     *
     * @var string
     */
    public $clientSecret;

    /**
     * Create a new Client.
     *
     * @return void
     */
    public function createClient(ClientRepository $clients)
    {
        $this->resetErrorBag();

        Validator::make([
            'name' => $this->createForm['name'],
            'redirect' => $this->createForm['redirect'],
        ], [
            'name' => ['required', 'string', 'max:255'],
            'redirect' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createClient');

        $client = $clients->create(
            $this->user->id,
            $this->createForm['name'],
            $this->createForm['redirect'],
            null,
            false,
            false,
            $this->createForm['confidential']
        );

        $this->displaySecretValue($client->secret, $client->id);

        $this->createForm['name'] = '';
        $this->createForm['redirect'] = '';
        $this->createForm['confidential'] = true;

        $this->dispatch('created');
    }

    /**
     * Display the client id and secret to the user.
     *
     * @param  string  $clientSecret
     * @param  string  $newClientId
     * @return void
     */
    protected function displaySecretValue($clientSecret, $newClientId)
    {
        $this->displayingSecret = true;

        $this->clientSecret = $clientSecret;
        $this->newClientId = $newClientId;

        $this->dispatch('showing-secret-modal');
    }

    /**
     * Allow the given Client to be managed.
     *
     * @param  string  $clientId
     * @return void
     */
    public function manageClient($managingClientId, ClientRepository $clients)
    {
        $this->managingClient = true;

        $client = $clients->findForUser($managingClientId, $this->user->id);

        $this->managingClientId = $client->id;

        $this->updateForm['name'] = $client->name;
        $this->updateForm['redirect'] = $client->redirect;
        $this->updateForm['confidential'] = true;

        $this->dispatch('showing-manage-client-modal');
    }

    /**
     * Update the Client.
     *
     *
     * @return void
     */
    public function updateClient(ClientRepository $clients)
    {
        $client = $clients->findForUser($this->managingClientId, $this->user->id);
        $clients->update($client, $this->updateForm['name'], $this->updateForm['redirect']);

        $this->managingClient = false;
    }

    /**
     * Confirm that the given API token should be deleted.
     */
    public function confirmDeletion($clientIdBeingDeleted): void
    {
        $this->confirmingDeletion = true;

        $this->clientIdBeingDeleted = $clientIdBeingDeleted;
    }

    /**
     * Delete the Client.
     *
     *
     * @return void
     */
    public function deleteClient(ClientRepository $clients)
    {
        $client = $clients->findForUser($this->clientIdBeingDeleted, $this->user->id);

        $clients->delete($client);

        $this->user->load('clients');

        $this->confirmingDeletion = false;

        $this->managingClientId = null;
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('jetstream-passport::oauth-client-manager');
    }
}
