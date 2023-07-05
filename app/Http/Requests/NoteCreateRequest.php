<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LogicException;

class NoteCreateRequest extends FormRequest
{
    final public const FIELD_TEXT = 'text';
    final public const FIELD_TITLE = 'title';

    final public const FIELD_PASSWORD = 'encrypt_password';

    final public const FIELD_EXPIRATION_DATE = 'expiration_date';

    final public const FIELD_ACCESS_RESTRICTION = 'access_restriction';


    public function rules(): array
    {
        return [
            self::FIELD_TEXT            => 'string|required|max:20000',
            self::FIELD_TITLE            => 'string|required|max:255',
            self::FIELD_PASSWORD        => 'string|min:6|max:100|nullable',
            self::FIELD_EXPIRATION_DATE => 'string|nullable',
            self::FIELD_ACCESS_RESTRICTION => 'string|nullable',
        ];
    }

    public function getText(): string
    {
        if (! is_string($this->get(self::FIELD_TEXT))) {
            throw new LogicException('Field `text` not string');
        }

        return $this->get(self::FIELD_TEXT);
    }

    public function getTitle(): string
    {
        if (! is_string($this->get(self::FIELD_TITLE))) {
            throw new LogicException('Field `title` not string');
        }

        return $this->get(self::FIELD_TITLE);
    }

    public function getPassword(): ?string
    {
        if ($this->get(self::FIELD_PASSWORD) === null) {
            return null;
        }

        if (! is_string($this->get(self::FIELD_PASSWORD))) {
            throw new LogicException('Field `password` not string');
        }

        return $this->get(self::FIELD_PASSWORD);
    }

    public function getAccessRestriction(): ?string
    {
        if ($this->get(self::FIELD_ACCESS_RESTRICTION) === null) {
            return null;
        }

        if (! is_string($this->get(self::FIELD_ACCESS_RESTRICTION))) {
            throw new LogicException('Field `access_restriction` not string');
        }

        return in_array($this->get(self::FIELD_ACCESS_RESTRICTION), ['public', 'unlisted', 'private'])
            ? $this->get(self::FIELD_ACCESS_RESTRICTION)
            : null;
    }

    public function getExpirationDate(): ?string
    {
        if ($this->get(self::FIELD_EXPIRATION_DATE) === null) {
            return null;
        }

        if (! is_string($this->get(self::FIELD_EXPIRATION_DATE))) {
            throw new LogicException('Field `expiration_date` not string');
        }

        return in_array($this->get(self::FIELD_EXPIRATION_DATE), ['1_hour', '1_day', '1_week', '1_month'])
            ? $this->get(self::FIELD_EXPIRATION_DATE)
            : null;
    }
}
