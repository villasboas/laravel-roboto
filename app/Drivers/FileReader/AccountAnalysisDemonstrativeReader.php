<?php

namespace App\Drivers\FileReader;

use App\Contracts\Services\FileReaderService;
use App\Contracts\Drivers\FileReaderDriver;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use App\Rules\MoneyRule;

class AccountAnalysisDemonstrativeReader implements FileReaderDriver
{
    /**
     * File content collection
     *
     * @var Collection
     */
    protected Collection $fileContent;

    /**
     * Protocols
     *
     * @var array
     */
    protected array $protocols = [];

    /**
     * Protocol stack
     *
     * @var array
     */
    protected $protocolStack;

    /**
     * Guide stack
     *
     * @var array
     */
    protected $guideStack;

    /**
     * File content output
     *
     * @var array
     */
    protected array $output = [];

    /**
     * Push a protocol into the stack
     *
     * @param array $data
     * @return FileReaderDriver
     */
    public function pushProtocol(array $data): FileReaderDriver
    {
        $this->protocolStack = array_merge($this->protocolStack ?? [], $data);

        return $this;
    }

    /**
     * Remove the protocol from the stack
     *
     * @param array $data
     * @return array
     */
    public function popProtocol(array $data): array
    {
        $protocol = array_merge($this->protocolStack, $data);

        $this->protocolStack = null;

        $this->protocols[] = $protocol;

        return $protocol;
    }

    /**
     * Push a guide into the stack
     *
     * @param array $data
     * @return FileReaderDriver
     */
    public function pushGuide(array $data): FileReaderDriver
    {
        if ($this->hasOpenGuide()) {
            $this->guideStack = array_merge($this->guideStack, $data);
        } else {
            $this->guideStack = $data;
        }

        return $this;
    }

    /**
     * Pop a guide from the stack
     *
     * @param array $data
     * @return array
     */
    public function popGuide(array $data): array
    {
        $guide = array_merge($this->guideStack, $data);

        $this->guideStack = null;

        if (optional($this->protocolStack)['Guias']) {
            $this->protocolStack['Guias'][] = $guide;
        } else {
            $this->protocolStack['Guias'] = [$guide];
        }

        return $guide;
    }

    /**
     * Push a table line into the guide
     *
     * @param array $data
     * @return array
     */
    public function pushTableLine(array $data): array
    {
        if (!$this->hasOpenGuide()) {
            return null;
        }

        if (optional($this->guideStack)['Items']) {
            $this->guideStack['Items'][] = $data;
        } else {
            $this->guideStack['Items'] = [$data];
        }

        return $data;
    }

    /**
     * Check if a given schema is valid
     *
     * @param array $data
     * @param array $rules
     * @return bool
     */
    public function isValidSchema(array $data, array $rules): bool
    {
        return !Validator::make($data, $rules)->fails();
    }

    /**
     * Fetch document heading
     *
     * @param array $data
     * @return array | bool
     */
    public function isDocumentHeading(array $data): array | bool
    {
        if (!$this->isValidSchema($data, [
            'required',
            'required',
            'required|cnpj',
            'required',
        ])) {
            return false;
        }

        return [
            'Registro_ANS'     => optional($data)[0],
            'Nome_Operadora' => optional($data)[1],
            'CNPJ_Operadora'   => optional($data)[2],
            'Data_Emissao'     => optional($data)[3]
        ];
    }

    /**
     * Fetch provider heading
     *
     * @param array $data
     * @return array | bool
     */
    public function isProviderHeading(array $data): array | bool
    {
        if (!$this->isValidSchema($data, [
            'required|numeric',
            'required',
            'required|numeric',
        ])) {
            return false;
        }

        return [
            'Codigo_Operadora' => optional($data)[0],
            'Nome_Contratado'  => optional($data)[1],
            'Codigo_CNES'      => optional($data)[2]
        ];
    }

    /**
     * Fetch protocol heading
     *
     * @param array $data
     * @return array | bool
     */
    public function isProtocolHeading(array $data): array | bool
    {
        if (!$this->isValidSchema($data, [
            'required|numeric',
            'required|numeric',
            'required',
            'required',
        ])) {
            return false;
        }

        return [
            'Numero_Lote'               => optional($data)[0],
            'Numero_Protocolo'          => optional($data)[1],
            'Data_Protocolo'            => optional($data)[2],
            'Codigo_Glosa_Protocolo'    => '',
            'Codigo_Situacao_Protocolo' => optional($data)[3],
        ];
    }

    /**
     * Fetch guide heading
     *
     * @param array $data
     * @return array | bool
     */
    public function isGuideHeading(array $data): array | bool
    {
        if(!$this->isValidSchema($data, [
            'required|numeric',
            'required|numeric',
            'nullable',
        ]) || count($data) > 3) {
            return false;
        }

        return [
            'Numero_Guia_Prestador'  => optional($data)[0],
            'Numero_Guia_Prestadora' => optional($data)[1],
            'Senha'                  => '',
        ];
    }

    /**
     * Fetch guide recipient
     *
     * @param array $data
     * @return array | bool
     */
    public function isGuideRecipient(array $data): array | bool
    {
        if (!$this->isValidSchema($data, [
            'required|string',
            'required|numeric|min:10000000000'
        ]) || count($data) !== 2) {
            return false;
        }

        return [
            'Nome_Beneficiario' => optional($data)[0],
            'Numero_Carteira'   => optional($data)[1]
        ];
    }

    /**
     * Fetch guide start billing line
     *
     * @param array $data
     * @return array | bool
     */
    public function isGuideStartBilling(array $data): array | bool
    {
        if(!$this->isValidSchema($data, [
            'required|date',
            'nullable'
        ]) || count($data) >= 3) {
            return false;
        }

        return [
            'Data_Inicio_Faturamento' => optional($data)[0],
            'Hora_Inicio_Faturamento' => '',
            'Data_Fim_Faturamento'    => '',
            'Hora_Fim_Faturamento'    => '',
            'Codigo_Glosa_Guia'       => '',
            'Codigo_Situacao_Guia'    => optional($data)[1],
        ];
    }

    /**
     * Fetch guide table line
     *
     * @param array $data
     * @return array | bool
     */
    public function isGuideTableLine(array $data): array | bool
    {
        if (!$this->isValidSchema($data, [
            'required|date_format:d/m/Y',
            'required|numeric',
            'required',
            'nullable',
            'nullable',
            'nullable',
            'nullable',
            'required',
        ])) {
            return false;
        }

        return [
            'Data_Realizacao'      => optional($data)[0],
            'Tabela'               => optional($data)[1],
            'Codigo_Procedimento'  => optional($data)[2],
            'Descricao'            => optional($data)[3],
            'Grau_Participacao'    => "",
            'Valor_Informado'      => optional($data)[4],
            'Quantidade_Executada' => optional($data)[5],
            'Valor_Processado'     => optional($data)[6],
            'Valor_Liberado'       => optional($data)[7],
            'Valor_Glosa'          => '',
            'Codigo_Glosa'         => '',
        ];
    }

    /**
     * Fetch guide total
     *
     * @param array $data
     * @return array | bool
     */
    public function isGuideTotal(array $data): array | bool
    {
        if (!$this->isValidSchema($data, [
            ['required', new MoneyRule],
            ['required', new MoneyRule],
            ['required', new MoneyRule],
            ['required', new MoneyRule],
        ]) || !$this->hasOpenGuide()) {
            return false;
        }

        return [
            'Valor_Informado_Guia'  => optional($data)[0],
            'Valor_Processado_Guia' => optional($data)[1],
            'Valor_Liberado_Guia'   => optional($data)[2],
            'Valor_Glosa_Guia'      => optional($data)[3],
        ];
    }

    /**
     * Fetch protocol total
     *
     * @param array $data
     * @return array | bool
     */
    public function isProtocolTotal(array $data): array | bool
    {
        if (!$this->isValidSchema($data, [
            ['required', new MoneyRule],
            ['required', new MoneyRule],
            ['required', new MoneyRule],
            ['required', new MoneyRule],
        ]) || $this->hasOpenGuide() || !$this->hasOpenProtocol()) {
            return false;
        }

        return [
            'Valor_Informado_Protocolo'  => optional($data)[0],
            'Valor_Processado_Protocolo' => optional($data)[1],
            'Valor_Liberado_Protocolo'   => optional($data)[2],
            'Valor_Glose_Protocolo'      => optional($data)[3],
        ];
    }

    /**
     * Check if there is a guide open
     *
     * @return bool
     */
    public function hasOpenGuide(): bool
    {
        return !!$this->guideStack;
    }

    /**
     * Check if there is a protocol open
     *
     * @return bool
     */
    public function hasOpenProtocol(): bool
    {
        return !!$this->protocolStack;
    }

    /**
     * Set document content to be precessed
     *
     * @param Collection $fileContent
     * @return FileReaderDriver
     */
    public function setContent(Collection $fileContent): FileReaderDriver
    {
        $this->fileContent = $fileContent;

        return $this;
    }

    /**
     * Get document content after being processed
     *
     * @return Collection
     */
    public function getContent(): Collection
    {
        $this->fileContent->each(function($line) {
            $data = collect(explode(FileReaderService::SEPARATOR, trim($line, FileReaderService::SEPARATOR)))
            ->values()
            ->toArray();

            if ($result = $this->isDocumentHeading($data)) {
                $this->output = array_merge($this->output, $result);
                return;
            }

            if ($result = $this->isProviderHeading($data)) {
                $this->output = array_merge($this->output, $result);
                return;
            }

            if ($result = $this->isProtocolHeading($data)) {
                $this->pushProtocol($result);
                return;

            }

            if ($result = $this->isGuideHeading($data)) {
                $this->pushGuide($result);
                return;

            }

            if ($result = $this->isGuideRecipient($data)) {
                $this->pushGuide($result);
                return;

            }

            if ($result = $this->isGuideStartBilling($data)) {
                $this->pushGuide($result);
                return;

            }

            if ($result = $this->isGuideTableLine($data)) {
                $this->pushTableLine($result);
                return;

            }

            if ($result = $this->isGuideTotal($data)) {
                $this->popGuide($result);
                return;
            }

            if ($result = $this->isProtocolTotal($data)) {
                $this->popProtocol($result);
                return;
            }
        });

        $this->output['Protocolos'] = $this->protocols;

        return collect($this->output);
    }
}
