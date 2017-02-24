<?php

namespace BerryGoudswaard\Command;

use BerryGoudswaard\HttpMessages\BeginReleaseUploadRequest;
use BerryGoudswaard\HttpMessages\BeginReleaseUploadResponse;
use BerryGoudswaard\HttpMessages\CommitReleaseUploadRequest;
use BerryGoudswaard\HttpMessages\CommitReleaseUploadResponse;
use BerryGoudswaard\HttpMessages\ReleaseUploadRequest;
use BerryGoudswaard\HttpMessages\UpdateReleaseRequest;
use GuzzleHttp\ClientInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReleaseDistributeCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('release:distribute')
            ->setDescription('Distribute a new release.')
            ->setHelp('This command allows distribute a new release.')
            ->addArgument('owner_name', InputArgument::REQUIRED, 'The name of the owner.')
            ->addArgument('app_name', InputArgument::REQUIRED, 'The name of the application.')
            ->addArgument('api_token', InputArgument::REQUIRED, 'The api token for the request.')
            ->addArgument('file', InputArgument::REQUIRED, 'The APK or IPA file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->notice('Begin a new release.');
        $ownerName = $input->getArgument('owner_name');
        $appName = $input->getArgument('app_name');
        $apiToken = $input->getArgument('api_token');
        $file = $input->getArgument('file');

        if (!file_exists($file)) {
            $this->logger->error('The file does not exists: ' . $file);
            return 1;
        }

        if (!($beginReleaseUploadResponse = $this->beginReleaseUpload($ownerName, $appName, $apiToken))) {
            $this->logger->error('Could not begin a new release.');
            return 1;
        }

        $uploadId = $beginReleaseUploadResponse->getUploadId();
        $uploadUrl = $beginReleaseUploadResponse->getUploadUrl();

        $this->logger->debug(sprintf('Upload ID: %s', $uploadId));
        $this->logger->debug(sprintf('Upload URL: %s', $uploadUrl));
        $this->logger->notice('Uploading release.');

        if (!$this->uploadRelease($uploadUrl, $file)) {
            $this->logger->error('Uploading the release failed.');
            return 1;
        }

        $this->logger->notice('Committing the release upload.');

        if (!($commitReleaseUploadResponse = $this->commitReleaseUpload($ownerName, $appName, $apiToken, $uploadId))) {
            $this->logger->error('Updating the release upload failed.');
            return 1;
        }

        $releaseUrl = $commitReleaseUploadResponse->getReleaseUrl();
        $this->logger->debug(sprintf('Release URL: %s', $releaseUrl));
        $this->logger->notice('Updating the release.');

        if (!$this->updateRelease($ownerName, $appName, $apiToken, $releaseUrl)) {
            $this->logger->error('Updating the release failed.');
            return 1;
        }

        $this->logger->notice('Distribution completed.');
    }

    private function beginReleaseUpload($ownerName, $appName, $apiToken)
    {
        $request = new BeginReleaseUploadRequest($ownerName, $appName, $apiToken);
        $response = $this->client->send($request);

        if ($response->getStatusCode() != 201) {
            return;
        }

        return new BeginReleaseUploadResponse($response);
    }

    private function uploadRelease($uploadUrl, $file)
    {
        $request = new ReleaseUploadRequest($uploadUrl, $file);
        $response = $this->client->send($request, $request->getOptions());

        if ($response->getStatusCode() != 204) {
            return;
        }

        return true;
    }

    private function commitReleaseUpload($ownerName, $appName, $apiToken,$uploadId)
    {
        $request = new CommitReleaseUploadRequest($ownerName, $appName, $apiToken, $uploadId);
        $response = $this->client->send($request);

        if ($response->getStatusCode() != 200) {
            return;
        }

        return new CommitReleaseUploadResponse($response);
    }

    private function updateRelease($ownerName, $appName, $apiToken,$releaseUrl)
    {
        $request = new UpdateReleaseRequest($ownerName, $appName, $apiToken, $releaseUrl);
        $response = $this->client->send($request);

        if ($response->getStatusCode() != 200) {
            return;
        }

        return true;
    }
}
